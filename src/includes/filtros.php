<div class="offcanvas offcanvas-end" tabindex="-1" id="filtros_mob" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasRightLabel">Filtros</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="container">
            <div class="row align-items-start">
                <div class="filtros-mobile w-100">
                    <!-- Categorias -->
                    <div>
                        <h6>Categories</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="defaultCategory1">
                            <label class="form-check-label" for="defaultCategory1">
                                Apparel
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="defaultCategory2">
                            <label class="form-check-label" for="defaultCategory2">
                                Accessories
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="defaultCategory3">
                            <label class="form-check-label" for="defaultCategory3">
                                Home & Living
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="defaultCategory4">
                            <label class="form-check-label" for="defaultCategory4">
                                Stationery
                            </label>
                        </div>
                    </div>
                    <hr>
                    <div class="range-container">
                        <h6 class="mb-3 fw-semibold">Price Range</h6>
                        <div class="double-range-slider">
                            <div class="slider-track">
                                <div class="slider-track-highlight" id="track-highlight-mobile"></div>
                            </div>
                            <div class="slider-thumb" id="thumb-min-mobile"></div>
                            <div class="slider-thumb" id="thumb-max-mobile"></div>
                            <input type="range" class="range-input" id="range-min-mobile" min="0" max="100" value="30">
                            <input type="range" class="range-input" id="range-max-mobile" min="0" max="100" value="70">
                        </div>
                        <div class="price-labels mt-2">
                            <span class="price-label">$<span id="value-min-mobile">30</span></span>
                            <span class="price-label">$<span id="value-max-mobile">70</span></span>
                        </div>
                    </div>
                    <hr>
                    <div class="color-selector">
                        <h6>Colors</h6>
                        <div id="idColorOptionsMobile" class="colorOptions d-flex gap-2">
                            <input type="radio" class="btn-check" name="color-mobile" id="color-red-mobile"
                                autocomplete="off">
                            <label class="btnColor rounded-circle p-2" for="color-red-mobile"
                                style="background-color: red; border: 2px solid #ccc;"></label>

                            <input type="radio" class="btn-check" name="color-mobile" id="color-blue-mobile"
                                autocomplete="off">
                            <label class="btnColor rounded-circle p-2" for="color-blue-mobile"
                                style="background-color: blue; border: 2px solid #ccc;"></label>

                            <input type="radio" class="btn-check" name="color-mobile" id="color-green-mobile"
                                autocomplete="off">
                            <label class="btnColor rounded-circle p-2" for="color-green-mobile"
                                style="background-color: green; border: 2px solid #ccc;"></label>

                            <input type="radio" class="btn-check" name="color-mobile" id="color-orange-mobile"
                                autocomplete="off">
                            <label class="btnColor rounded-circle p-2" for="color-orange-mobile"
                                style="background-color: orange; border: 2px solid #ccc;"></label>

                            <input type="radio" class="btn-check" name="color-mobile" id="color-purple-mobile"
                                autocomplete="off">
                            <label class="btnColor rounded-circle p-2" for="color-purple-mobile"
                                style="background-color: purple; border: 2px solid #ccc;"></label>

                            <input type="radio" class="btn-check" name="color-mobile" id="color-black-mobile"
                                autocomplete="off">
                            <label class="btnColor rounded-circle p-2" for="color-black-mobile"
                                style="background-color: black; border: 2px solid #ccc;"></label>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <h6>Size</h6>
                        <div id="idSizeOptions-mobile" class="sizeOptions"></div>
                    </div>
                    <hr>
                    <div>
                        <h6>Promotions</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="promoOnSale">
                            <label class="form-check-label" for="promoOnSale">
                                On Sale
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="promoHomeLiving">
                            <label class="form-check-label" for="promoHomeLiving">
                                Home & Living
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="promoStationery">
                            <label class="form-check-label" for="promoStationery">
                                Stationery
                            </label>
                        </div>
                    </div>
                    <hr>
                    <!-- Botão de Aplicar -->
                    <button id="apply-filters" class="btn btn-primary">Apply Filters</button>
                </div>
            </div>
            <hr>
        </div>
    </div>
</div>