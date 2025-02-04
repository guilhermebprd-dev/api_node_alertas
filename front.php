<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulário de agendamento de alertas CI</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script type="text/javascript"
    src="https://gc.kis.v2.scr.kaspersky-labs.com/FD126C42-EBFA-4E12-B309-BB3FDD723AC1/main.js?attr=EwPEnVYPt7yK3G6sG_P4oiGTHscFpbrz_O8dEJKZ8Qv9OaV0foVbMjVPo0RoSoLpvo970DHQf0JYyhn9sRKr-zz4SOV_I2qNbCxFb8Q9uYc"
    charset="UTF-8"></script>
  <link rel="stylesheet" crossorigin="anonymous"
    href="https://gc.kis.v2.scr.kaspersky-labs.com/E3E8934C-235A-4B0E-825A-35A08381A191/abn/main.css?attr=aHR0cHM6Ly93d3cuY2VudHJvZGFpbnRlbGlnZW5jaWEuY29tLmJyL2FsZXJ0YXMv" />
  <style>
    body {
      background-color: #f8f9fa;
      font-family: Arial, sans-serif;
    }

    .form-container {
      max-width: 600px;
      margin: 50px auto;
      background-color: #ffffff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-logo {
      text-align: center;
      margin-bottom: 20px;
    }

    .form-logo img {
      max-width: 100px;
    }

    .form-title {
      text-align: center;
      margin-bottom: 20px;
      font-weight: bold;
      font-size: 1.5rem;
      color: #333;
    }

    .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
    }

    .btn-primary:hover {
      background-color: #0056b3;
      border-color: #004085;
    }
  </style>
</head>

<body>

  <div class="container form-container">
    <div class="form-logo">
      <img src="logo.png" alt="Logo">
    </div>
    <h2 class="form-title">CIZAP ALERTAS</h2>
    <form id="alertForm">
      <div class="mb-3">
        <label for="datetime" class="form-label">Data e Hora</label>
        <input type="datetime-local" class="form-control" id="datetime" required>
      </div>
      <div class="mb-3">
        <label for="message" class="form-label">Lembrete</label>
        <textarea require class="form-control" id="message" rows="4" placeholder="Digite sua mensagem aqui..."
          required></textarea>
      </div>
      <div class="mb-3">
        <label for="phone-select" class="form-label">Telefone</label>
        <select class="form-select" id="phone-select" required>
          <option value="" disabled selected>Selecione...</option>
          <option value="5514996010303">Danilo</option>
          <option value="5514996165397">Saulo</option>
          <option value="5514981190192">Guilherme</option>
          <option value="5514998009447">Gabriel</option>
          <option value="5514997501274">Victor Hugo</option>
          <option value="5514991643110">Luis Felipe</option>
          <option value="5514997581800">Brunna</option>
          <option value="5514991627883">Juliana</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary w-100">Enviar</button>
    </form>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="resultModalLabel">Resultado do Envio</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p id="modalMessage"></p>
          <ul id="recordsList"></ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS (opcional) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- jQuery (necessário para o AJAX) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Bootstrap JS (para o modal) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    $(document).ready(function () {
      $('#alertForm').on('submit', function (event) {
        event.preventDefault(); // Evitar o envio padrão do formulário

        const form = document.getElementById('alertForm');

        // Validação manual
        let datetime = $('#datetime').val();
        let message = $('#message').val();
        let phone = $('#phone-select').val();

        if (datetime === "" || message === "" || phone === null) {
          alert("Por favor, preencha todos os campos.");
          return;
        }

        // Enviar os dados via AJAX
        $.ajax({
          url: 'http://localhost:3000/setAlert', 
          method: 'POST',
          data: {
            datetime: datetime,
            message: message,
            phone: phone
          },
          success: function (response) {
            // Supondo que o response seja um JSON com os dados e registros
            $('#modalMessage').html('Lista de alertas atualizada com sucesso!');
            let records = response.records; // Substitua pelo caminho correto
            $('#recordsList').empty(); // Limpar lista antes de adicionar novos itens

            records.forEach(function (record) {
              $('#recordsList').append('<li>' + record.date_alarm + ' - ' + record.text + '</li>');
            });

            // Mostrar o modal com os resultados
            $('#resultModal').modal('show');

            // Limpar os campos do formulário
            form.reset();

            // Resetar o seletor para a opção padrão
            document.getElementById('phone-select').selectedIndex = 0;
          },
          error: function (xhr, status, error) {
            $('#modalMessage').text('Erro ao cadastrar o registro.');
            $('#recordsList').empty(); // Limpar lista em caso de erro

            // Mostrar o modal com a mensagem de erro
            $('#resultModal').modal('show');
          }
        });
      });
    });
  </script>

</body>

</html>