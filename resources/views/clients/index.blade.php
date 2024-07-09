<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/clients">Clientes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/orders">Pedidos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/products">Produtos</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <h1 class="mt-5">Lista de Clientes</h1>
    <button type="button" class="btn btn-primary mt-3 mb-3" data-toggle="modal" data-target="#createClientModal">
        Novo Cliente
    </button>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
            <tr>
                <td>{{ $client->id }}</td>
                <td>{{ $client->nome }}</td>
                <td>{{ $client->telefone }}</td>
                <td>{{ $client->email }}</td>
                <td>
                    <a href="{{ route('clients.show', $client->id) }}" class="btn btn-primary btn-sm view-client">Visualizar</a>
                    <button class="btn btn-danger btn-sm delete-client" data-client-id="{{ $client->id }}">Excluir</button>
                </td>
            </tr>

            <!-- Modal de confirmação de exclusão para o cliente atual -->
            <div class="modal fade" id="confirmDeleteModal{{ $client->id }}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel{{ $client->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmDeleteModalLabel{{ $client->id }}">Confirmar Exclusão</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Tem certeza que deseja excluir o cliente "{{ $client->nome }}"?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <!-- Formulário de Exclusão -->
                            <form action="{{ route('clients.destroy', $client->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Confirmar Exclusão</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>
</div>


<!-- Modal para criar novo cliente -->
<div class="modal fade" id="createClientModal" tabindex="-1" role="dialog" aria-labelledby="createClientModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="createForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="createClientModalLabel">Novo Cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                    </div>
                    <div class="form-group">
                        <label for="telefone">Telefone</label>
                        <input type="text" class="form-control" id="telefone" name="telefone">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('.delete-client').on('click', function(e) {
            e.preventDefault();
            var clientId = $(this).data('client-id');
            $('#confirmDeleteModal' + clientId).modal('show');
        });

        // Submeter o formulário de criação de cliente via AJAX
        $('#createForm').submit(function(e) {
            e.preventDefault();
            var formData = {
                nome: $('#nome').val(),
                telefone: $('#telefone').val(),
                email: $('#email').val(),
            };
            $.ajax({
                url: '/api/clients',
                type: 'POST',
                dataType: 'json',
                data: formData,
                success: function(response) {
                    alert('Cliente criado com sucesso!');
                    $('#createClientModal').modal('hide');
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseJSON.message;
                    alert('Erro ao criar cliente: ' + errorMessage);
                }
            });
        });
    });
</script>

</body>
</html>
