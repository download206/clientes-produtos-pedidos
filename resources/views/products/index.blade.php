<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Produtos</title>
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
    <h1 class="mt-5">Lista de Produtos</h1>
    <button type="button" class="btn btn-primary mt-3 mb-3" data-toggle="modal" data-target="#createProductModal">
        Novo Produto
    </button>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Preço Unitário</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->description }}</td>
                <td>${{ number_format($product->unit_price, 2) }}</td>
                <td>
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary btn-sm">Visualizar</a>
                    <button class="btn btn-danger btn-sm delete-product" data-product-id="{{ $product->id }}">Excluir</button>
                </td>
            </tr>

            <!-- Modal de confirmação de exclusão para o produto atual -->
            <div class="modal fade" id="confirmDeleteModal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel{{ $product->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmDeleteModalLabel{{ $product->id }}">Confirmar Exclusão</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Tem certeza que deseja excluir o produto "{{ $product->name }}"?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <!-- Formulário de Exclusão -->
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline;">
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

<!-- Modal para criar novo produto -->
<div class="modal fade" id="createProductModal" tabindex="-1" role="dialog" aria-labelledby="createProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="createForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="createProductModalLabel">Novo Produto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nome</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Descrição</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="unit_price">Preço Unitário</label>
                        <input type="text" class="form-control" id="unit_price" name="unit_price" required>
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
        $('.delete-product').on('click', function(e) {
            e.preventDefault();
            var productId = $(this).data('product-id');
            $('#confirmDeleteModal' + productId).modal('show');
        });

        // Submeter o formulário de criação de produto via AJAX
        $('#createForm').submit(function(e) {
            e.preventDefault();
            var formData = {
                name: $('#name').val(),
                description: $('#description').val(),
                unit_price: $('#unit_price').val(),
            };
            $.ajax({
                url: '/api/products',
                type: 'POST',
                dataType: 'json',
                data: formData,
                success: function(response) {
                    alert('Produto criado com sucesso!');
                    $('#createProductModal').modal('hide');
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseJSON.message;
                    alert('Erro ao criar produto: ' + errorMessage);
                }
            });
        });
    });
</script>
</body>
</html>
