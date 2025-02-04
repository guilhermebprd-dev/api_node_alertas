// FUNÇÃO GENÉRICA PARA TRATAR AS RESPOSTAS DO SERVIDOR AO CLIENT
function enviarResposta(res, sucesso = true, mensagem, dados = {}, codigoStatus = 200) {
  return res.status(codigoStatus).json({
    status: sucesso ? "success" : "error",
    message: mensagem,
    ...(sucesso ? { data: dados } : {}),
  });
}

module.exports = {
  enviarResposta,
};

// ... = OPERADOR DE ESPALHAMENTO - USO PARA INCLUIR A PROPRIEDADE 'DATA' NO OBJETO DO RETORNO CASO 'STATUS' SEJA SUCESSO.
