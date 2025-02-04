// MODIFICA A DISPONIBILIDADE DA API E RETORNA UM STATUS 503 CASO A API ESTEJA INDISPONÍVEL
const API_AVAILABILITY = true;

const verificarDisponibilidadeAPI = (req, res, next) => {
  if (API_AVAILABILITY) {
    next();
  } else {
    res.status(503).json({
      sucesso: false,
      mensagem: "API temporariamente fora do ar",
    });
  }
};

module.exports = { verificarDisponibilidadeAPI };