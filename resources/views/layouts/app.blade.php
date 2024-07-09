<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Pedidos</title>
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
        <h1 class="mt-5">Lista de Pedidos</h1>
        <a href="{{ route('orders.create') }}" class="btn btn-primary mb-3">Criar Pedido</a>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->client->nome }}</td>
                    <td>{{ $order->status }}</td>
                    <td>
                        <a href="#" class="btn btn-primary btn-sm" onclick="viewOrder({{ $order->id }})" data-toggle="modal" data-target="#viewOrderModal">Visualizar</a>
                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este pedido?')">Excluir</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal de visualização de pedido -->
    <div class="modal fade" id="viewOrderModal" tabindex="-1" aria-labelledby="viewOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewOrderModalLabel">Detalhes do Pedido</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Conteúdo dinâmico dos detalhes do pedido será carregado aqui via JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        function viewOrder(id) {
            $.ajax({
                url: '/api/orders/' + id,
                type: 'GET',
                success: function(response) {
                    // Preencher os detalhes do pedido no modal de visualização
                    var modalBody = $('.modal-body');
                    modalBody.empty();

                    modalBody.append('<p><strong>ID:</strong> ' + response.order.id + '</p>');
                    modalBody.append('<p><strong>Cliente:</strong> ' + response.order.client.nome + '</p>');
                    modalBody.append('<p><strong>Status:</strong> ' + response.order.status + '</p>');
                    modalBody.append('<p><strong>Descrição:</strong> ' + response.order.description + '</p>');

                    modalBody.append('<h5>Produtos:</h5>');
                    var productsList = $('<ul></ul>');
                    $.each(response.order.products, function(index, product) {
                        productsList.append('<li>' + product.name + ' - Quantidade: ' + product.pivot.quantity + '</li>');
                    });
                    modalBody.append(productsList);

                    $('#viewOrderModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao buscar detalhes do pedido:', error);
                    alert('Erro ao buscar detalhes do pedido. Verifique o console para mais detalhes.');
                }
            });
        }
    </script>
</body>
</html>
