<?php
// Array simulando itens do carrinho
$cart_items = [
    [
        'name' => 'Premium Cotton T-shirt',
        'size' => 'L',
        'color' => 'White',
        'price' => 29.99,
        'quantity' => 1,
        'image' => 'imagens/produtos varios hero.png'
    ],
    [
        'name' => 'Premium Cotton T-shirt',
        'size' => 'L',
        'color' => 'White',
        'price' => 29.99,
        'quantity' => 1,
        'image' => 'imagens/produtos varios hero.png'
    ]
];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="carrinhoTeste.css">
</head>

<body>
    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
        aria-controls="offcanvasRight">Carrinho</button>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasRightLabel">Shopping Cart (<?php echo count($cart_items); ?>)</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="container">
                <?php foreach ($cart_items as $item): ?>
                    <div class="row align-items-start">
                        <div class="col-4">
                            <img src="<?php echo $item['image']; ?>" class="img-small" alt="produtos">
                        </div>
                        <div class="col-8 d-flex justify-content-between align-items-start">
                            <div>
                                <p class="product-info mb-1"><?php echo $item['name']; ?></p>
                                <span class="product-info">Size: <?php echo $item['size']; ?></span>
                                <span class="product-info"> | </span>
                                <span class="product-info">Color: <?php echo $item['color']; ?></span>
                                <!-- Quantidade e Preço -->
                                <div class="d-flex align-items-center mt-2">
                                    <button type="button" class="btn btn-outline-secondary me-2">-</button>
                                    <span class="me-2">1</span>
                                    <button type="button" class="btn btn-outline-secondary me-3">+</button>
                                    <span class="price"><?php echo number_format($item['price'], 2); ?>€</span>
                                </div>
                            </div>
                            <div>
                                <button type="button" class="btn btn-link p-0">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <hr>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="produtos.js"></script>
</body>
</html>