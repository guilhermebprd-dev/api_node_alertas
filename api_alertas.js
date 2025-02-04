const express = require("express");
const mysql = require("mysql2/promise");
const cors = require("cors");

// UTILITARIOS PARA SERVIDOR
const configuracaoMySQL = require("./inc/mysql_config");
const { enviarResposta } = require("./inc/functions");
const {
  verificarDisponibilidadeAPI,
} = require("./middlewares/verificarDisponibilidadeAPI");

// CONFIG BASE SERVIDOR
const app = express();
const porta = 3000;

// INICIAR SERVIDOR
app.listen(porta, () => {
  console.log(`Servidor Iniciado na porta ${porta}`);
});

// VERIFICA SE .API ESTÁ DISPONÍVEL
app.use(verificarDisponibilidadeAPI);

// PREPARAR PARA REQUISIÇÃO JSON
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// PERMITE QUALQUER UM REQUERIR DO SERVIDOR
app.use(cors());

// POOL PARA OPTMIZAÇÃO DE QUERYS
const pool = mysql.createPool(configuracaoMySQL);

// ROTA PARA CRIAR ALERTA
app.post("/setAlert", async (req, res) => {
  const { datetime, message, phone } = req.body;

  // VALIDAÇÃO BÁSICA CAMPOS VAZIOS
  if (!datetime || !message || !phone) {
    return enviarResposta(
      res,
      false,
      "Por favor, preencha todos os campos.",
      {},
      400
    );
  }

  // VALIDAÇÃO DATA ATUAL OU MENOR
  const dataAtual = new Date();
  const dataInserida = new Date(datetime);

  if (dataInserida <= dataAtual) {
    return enviarResposta(
      res,
      false,
      "A data e hora devem ser posteriores ao momento atual.",
      {},
      400
    );
  }

  try {
    // INSERÇÃO
    const [resultadoInsercao] = await pool.query(
      "INSERT INTO ci_alertas (datetime, message, phone, criado_em) VALUES(?, ?, ?, NOW())",
      [datetime, message, phone]
    );

    // CONSULTAR ALERTAS FUTUROS
    const [records] = await pool.query(
      "SELECT * FROM ci_alertas WHERE phone = ? AND datetime >= NOW()",
      [phone]
    );

    // RESPOSTA PARA O FRONT-END
    return enviarResposta(
      res,
      true,
      "Alerta inserido com sucesso!",
      {
        linhasAfetadas: resultadoInsercao.affectedRows,
        alertasFuturos: records,
      },
      200
    );
  } catch (erro) {
    // RESPOSTA DE ERRO
    return enviarResposta(res, false, erro.message, {}, 500);
  }
});
