<div class="col-md-3 text-center mb-4">
    <div style="--dynamic-color: <?php echo getfirstColor($shoe['img']); ?>;" class="product">

        <!-- Badge Overlay with Buttons at the Bottom -->
        <div class="badge-overlay d-flex flex-column h-100">
            <?php if ($shoe['idSale'] != 0): ?>
                <span
                    class="<?= htmlspecialchars($shoe['pos']) ?> badge dynamic-color"
                    style="background-color: <?= htmlspecialchars($shoe['color']) ?>;"
                    <?php if (!empty($shoe['bigsales'])): ?>
                    onclick="window.location.href='info.php?product=<?= urlencode($shoe['id']) ?>'"
                    <?php endif; ?>>
                    <?= htmlspecialchars($shoe['salesName']) ?>
                </span>
                <input type="hidden" id="selectedProductId" value="<?= $shoe['id'] ?>">
                <input type="hidden" id="selectedProductColor" value="<?php echo getfirstColor($shoe['img']); ?>">
            <?php endif; ?>

            <!-- Buttons Row Positioned at Bottom -->
            <div class="row align-self-center w-100 mt-auto mb-4">
                <div class="col-6">
                    <button onclick="window.location.href='../Shoes/product/product.php?id=<?php echo $shoe['id']; ?>'" data-id="<?php echo $shoe['id']; ?>" class="btn btn-primary w-100" data-toggle="modal" data-target="#productModal">
                        <i class="material-icons">visibility</i>
                    </button>
                </div>
                <div class="col-6">
                    <button class="btn btn-success w-100" onclick="openSizeModal(<?php echo $shoe['id']; ?>)">
                        <i class="material-icons">shopping_cart</i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Product Image and Name -->
        <div class="row align-self-start">
            <div style="--dynamic-color: <?php echo getfirstColor($shoe['img']); ?>;" class="circle"></div>
            <div class="col-md-12 pt-4">
                <img style="min-height: 106px;" src="../Admin/img/<?php echo getfirstImg($shoe['img']); ?>" alt="Product Shoes">
            </div>
            <div class="col-md-12 pt-md-3 pb-sm-4">
                <h4><?php echo htmlspecialchars($shoe['name']); ?></h4>
            </div>
        </div>
    </div>
</div>
