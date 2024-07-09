<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pedido</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
        <h1 class="mt-5">Editar Pedido</h1>
        <form id="edit-order-form" action="{{ route('orders.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status">
                    <option value="Em Aberto" {{ $order->status == 'Em Aberto' ? 'selected' : '' }}>Em Aberto</option>
                    <option value="Pago" {{ $order->status == 'Pago' ? 'selected' : '' }}>Pago</option>
                    <option value="Cancelado" {{ $order->status == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Descrição</label>
                <textarea class="form-control" id="description" name="description">{{ $order->description }}</textarea>
            </div>

            <hr>

            <div class="form-group">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" data-toggle="modal" data-target="#productSearchModal">
                        <i class="fa fa-search"></i> Buscar Produtos
                    </button>
                </div>
            </div>
            <div class="form-group">
                <label>Produtos Adicionados</label>
                <ul id="added-products-list">
                    @foreach ($order->products as $product)
                        <li id="added-product-{{ $product->id }}">
                            {{ $product->name }} - Quantidade:
                            <span id="added-product-quantity-{{ $product->id }}">{{ $product->pivot->quantity }}</span>
                            <a href="#" class="text-danger" onclick="removeAddedProduct(event, {{ $product->id }})">Excluir</a>
                            <input type="number" class="ml-2 form-control" style="width: 60px;" id="edit-quantity-{{ $product->id }}" value="{{ $product->pivot->quantity }}" min="1" onchange="updateQuantity({{ $product->id }})">
                        </li>
                    @endforeach
                </ul>
            </div>


            <div id="added-products-inputs">
                <!-- Input para produtos existentes -->
                @foreach ($order->products as $product)
                    <input type="hidden" name="products[{{ $product->id }}][product_id]" value="{{ $product->id }}">
                    <input type="hidden" name="products[{{ $product->id }}][quantity]" value="{{ $product->pivot->quantity }}">
                @endforeach
            </div>

            <hr>

            <button type="button" class="btn btn-primary" onclick="submitForm()">Salvar Alterações</button>
        </form>
    </div>

    <!-- Modal de Busca de Produtos -->
    <div class="modal fade" id="productSearchModal" tabindex="-1" role="dialog" aria-labelledby="productSearchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productSearchModalLabel">Buscar Produto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Campo de busca -->
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="searchProductInput" placeholder="Buscar por nome do produto">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="searchProductsByName($('#searchProductInput').val())"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    
                    <!-- Lista de produtos encontrados -->
                    <ul id="searchProductList" class="list-group">
                        <!-- Os produtos serão adicionados dinamicamente aqui via JavaScript -->
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" onclick="addSearchedProduct()">Adicionar Produto Selecionado</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    // Variável global para armazenar os produtos buscados
    var searchedProducts = [];

    // Simulação de produtos para exemplo
    var products = {!! json_encode($products) !!};

    // Função para redefinir a seleção de produtos ao abrir o modal
    $('#productSearchModal').on('show.bs.modal', function (e) {
        // Limpar a lista de produtos buscados
        $('#searchProductList').empty();

        // Redefinir a seleção dos produtos para false
        searchedProducts.forEach(function(product) {
            product.selected = false;
        });

        // Iniciar busca com campo vazio ao carregar o modal
        searchProductsByName('');
    });

    // Função para buscar produtos por nome
    function searchProductsByName(searchTerm) {
        // Limpar lista de produtos antes de adicionar novos resultados
        $('#searchProductList').empty();

        // Filtrar produtos que correspondem ao termo de busca
        searchedProducts = products.filter(function(product) {
            return product.name.toLowerCase().includes(searchTerm.toLowerCase());
        });

        // Adicionar produtos filtrados à lista
        searchedProducts.forEach(function(product) {
            var buttonLabel = product.selected ? 'Remover' : 'Selecionar';
            var buttonClass = product.selected ? 'btn-danger' : 'btn-primary';

            $('#searchProductList').append('<li class="list-group-item">' + product.name + ' - ' + product.description + ' <button type="button" class="btn btn-sm float-right ' + buttonClass + '" onclick="toggleProductSelection(' + product.id + ')">' + buttonLabel + '</button></li>');
        });
    }

    // Função para alternar a seleção de um produto na lista de busca
    function toggleProductSelection(productId) {
        var index = searchedProducts.findIndex(function(product) {
            return product.id === productId;
        });

        if (index !== -1) {
            searchedProducts[index].selected = !searchedProducts[index].selected;
            updateSelectedProductList();
        }
    }

    // Função para atualizar a lista de produtos selecionados
    function updateSelectedProductList() {
        $('#searchProductList').empty();

        searchedProducts.forEach(function(product) {
            var buttonLabel = product.selected ? 'Remover' : 'Selecionar';
            var buttonClass = product.selected ? 'btn-danger' : 'btn-primary';

            $('#searchProductList').append('<li class="list-group-item">' + product.name + ' - ' + product.description + ' <button type="button" class="btn btn-sm float-right ' + buttonClass + '" onclick="toggleProductSelection(' + product.id + ')">' + buttonLabel + '</button></li>');
        });
    }

    // Função para adicionar produto selecionado à lista de produtos a serem adicionados ao pedido
    function addSearchedProduct() {
        searchedProducts.forEach(function(product) {
            if (product.selected) {
                var existingProduct = $('#added-product-' + product.id);

                if (existingProduct.length > 0) {
                    // Produto já existe na lista, aumentar a quantidade
                    var currentQuantity = parseInt($('#added-product-quantity-' + product.id).text());
                    var newQuantity = currentQuantity + 1;
                    $('#added-product-quantity-' + product.id).text(newQuantity);
                    $('input[name="products[' + product.id + '][quantity]"]').val(newQuantity);
                } else {
                    // Produto não existe na lista, adicionar novo
                    $('#added-products-list').append(
                        '<li id="added-product-' + product.id + '">' + 
                        product.name + 
                        ' - Quantidade: <span id="added-product-quantity-' + product.id + '">1</span> ' + 
                        '<a href="#" class="text-danger" onclick="removeAddedProduct(event, ' + product.id + ')">Excluir</a>' + 
                        '<input type="number" class="ml-2 form-control" style="width: 60px;" id="edit-quantity-' + product.id + '" value="1" min="1" onchange="updateQuantity(' + product.id + ')">' +
                        '</li>'
                    );

                    $('#added-products-inputs').append(
                        '<input type="hidden" name="products[' + product.id + '][product_id]" value="' + product.id + '">' +
                        '<input type="hidden" name="products[' + product.id + '][quantity]" value="1">'
                    );
                }
            }
        });

        $('#productSearchModal').modal('hide');
        saveProductsToLocalStorage();
        updateTotalProductsCount();
    }

    // Função para atualizar a quantidade de produtos
    function updateQuantity(productId) {
        var quantity = $('#edit-quantity-' + productId).val();
        $('#added-product-quantity-' + productId).text(quantity);

        // Atualizar o campo oculto no formulário
        $('input[name="products[' + productId + '][quantity]"]').val(quantity);

        // Salvar a lista atualizada no localStorage
        saveProductsToLocalStorage();
    }


    // Função para salvar os produtos no localStorage
    function saveProductsToLocalStorage() {
        var productsToSave = [];

        $('#added-products-list li').each(function() {
            var productId = $(this).attr('id').replace('added-product-', '');
            var quantity = $('#edit-quantity-' + productId).val();

            productsToSave.push({
                id: productId,
                quantity: quantity
            });
        });

        localStorage.setItem('addedProducts', JSON.stringify(productsToSave));
    }


    // Função para carregar a lista de produtos do localStorage
    function loadProductsFromLocalStorage() {
        // Verificar se está no modal de criação de pedido
        if ($('#createOrderModal').hasClass('show')) {
            return; // Não fazer nada se estiver no modal de criação de pedido
        }

        var savedProducts = JSON.parse(localStorage.getItem('addedProducts')) || [];
        var productsToAdd = [];

        savedProducts.forEach(function(product) {
            // Verificar se o produto ainda existe na lista de produtos
            var existingProduct = products.find(p => p.id == product.id);
            if (existingProduct) {
                productsToAdd.push({
                    id: product.id,
                    name: existingProduct.name,
                    description: existingProduct.description,
                    quantity: product.quantity
                });
            }
        });

        // Limpar lista atual
        $('#added-products-list-create-modal').empty();
        $('#added-products-inputs-create-modal').empty();

        // Adicionar produtos válidos à lista visual
        productsToAdd.forEach(function(product) {
            $('#added-products-list-create-modal').append(
                '<li id="added-product-create-modal-' + product.id + '">' + 
                product.name + 
                ' - Quantidade: <span id="added-product-create-modal-quantity-' + product.id + '">' + product.quantity + '</span> ' + 
                '<a href="#" class="text-danger" onclick="removeAddedProductCreateModal(event, ' + product.id + ')">Excluir</a>' + 
                '</li>'
            );

            $('#added-products-inputs-create-modal').append(
                '<input type="hidden" name="products[' + product.id + '][product_id]" value="' + product.id + '">' +
                '<input type="hidden" name="products[' + product.id + '][quantity]" value="' + product.quantity + '">'
            );
        });
    }

    // Inicialização da lista de produtos
    $(document).ready(function() {
        // Atualizar contagem total inicialmente
        updateTotalProductsCount();

        // Iniciar busca com campo vazio ao carregar o modal
        searchProductsByName('');

        // Atualizar lista de produtos conforme o usuário digita
        $('#searchProductInput').on('input', function() {
            searchProductsByName($(this).val());
        });

        // Carregar produtos salvos do localStorage após a página ser completamente carregada
        loadProductsFromLocalStorage();
    });

    // Função para abrir o modal de criação de pedido
    $('#createOrderModal').on('show.bs.modal', function (e) {
        // Limpar a lista de produtos adicionados no modal de criação de pedido
        $('#added-products-list-create-modal').empty();
        $('#added-products-inputs-create-modal').empty();
    });


    // Função para remover produto adicionado
    function removeAddedProduct(event, productId) {
        event.preventDefault();
        $('#added-product-' + productId).remove();
        $('#added-products-inputs').find('input[name="products[' + productId + '][product_id]"]').remove();
        $('#added-products-inputs').find('input[name="products[' + productId + '][quantity]"]').remove();
        updateTotalProductsCount();
        
        // Remover o produto do localStorage
        var savedProducts = JSON.parse(localStorage.getItem('addedProducts')) || [];
        var updatedProducts = savedProducts.filter(function(product) {
            return product.id !== productId;
        });
        localStorage.setItem('addedProducts', JSON.stringify(updatedProducts));
    }


    // Inicialização da lista de produtos
    $(document).ready(function() {
        // Atualizar contagem total inicialmente
        updateTotalProductsCount();

        // Iniciar busca com campo vazio ao carregar o modal
        searchProductsByName('');

        // Atualizar lista de produtos conforme o usuário digita
        $('#searchProductInput').on('input', function() {
            searchProductsByName($(this).val());
        });

        // Carregar produtos salvos do localStorage após a página ser completamente carregada
        loadProductsFromLocalStorage();
    });


    // Função para submeter o formulário
    function submitForm() {
        var form = $('#edit-order-form');
        var url = form.attr('action');

        $.ajax({
            url: url,
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                alert('Pedido atualizado com sucesso.');
                window.location.href = '/orders'; // Redireciona para a página de índice de pedidos
            },
            error: function(xhr, status, error) {
                console.error('Erro ao atualizar o pedido:', error);
                alert('Erro ao atualizar o pedido. Verifique o console para mais detalhes.');
            }
        });
    }

    // Função para excluir produto do pedido
    function deleteProduct(event, productId) {
        event.preventDefault();
        if (confirm('Tem certeza que deseja excluir este produto do pedido?')) {
            $.ajax({
                url: '/api/orders/{{ $order->id }}/detach-product/' + productId,
                type: 'DELETE',
                success: function(response) {
                    alert('Produto removido do pedido com sucesso.');
                    $('#product-' + productId).remove();
                    saveProductsToLocalStorage();
                    updateTotalProductsCount();
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao remover produto do pedido:', error);
                    alert('Erro ao remover produto do pedido. Verifique o console para mais detalhes.');
                }
            });
        }
    }
</script>



</body>
</html>
