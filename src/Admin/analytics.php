<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="css/analytics.css">
    <script src="js/analytics.js"></script>
</head>

<style>
    body {
        margin: 0;
        padding: 0;
        overflow: auto;
        align-items: center;
    }

    #menu-mobile {
        display: none;
        position: absolute;
        top: 0;
        left: 0;
        width: 80%;
        height: 100%;
        background-color: white;
        transform: translateX(-10%);
        transition: transform 2000ms ease;
        overflow: hidden;
        z-index: 999;
        flex-direction: column-reverse;
        margin-top: 0;
        justify-content: flex-end;
        gap: 20px;
        align-items: center;
        padding-top: 100px;
    }

    .fixed-header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background-color: white;
        z-index: 999;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    #menu-mobile.open {
        display: flex;
        transform: translateX(0);
    }

    #menu-mobile a {
        color: #4F46E5;
        text-decoration: none;
        font-size: 1.5rem;
        margin: 15px 0;
    }

    #menu-toggle {
        display: none;
        position: fixed;
        top: 15px;
        left: 15px;
        z-index: 1000;
        font-size: 2rem;
        background: none;
        border: none;
        color: black;
        cursor: pointer;
    }

    #logo-header-mobile {
        display: none;
        background: none;
        border: none;
    }


    /* Ajustes para telas menores */
    @media (max-width: 1200px) {
        body {
            width: 100%;
            padding-top: 70px;
        }

        .header-desktop {
            display: none !important;
        }

        #a-logo-header-mobile {
            display: flex;
            width: max-content;
            justify-content: center;
            padding-top: 20px;
            padding-bottom: 20px;
            width: 100%;

        }

        #logo-header-mobile {
            display: block;
            width: 100px;
            height: auto;
            margin: 0 auto;
        }

        #menu-toggle {
            display: block;
            top: 0;
        }

        #menu-mobile {
            position: fixed;
            height: 100%;
            overflow: hidden;
        }

        #menu-mobile li {
            list-style: none;
        }

        #menu-mobile .social {
            display: flex;
            height: 100%;
            width: 100%;
            align-items: center;
            align-self: flex-end;
            justify-content: center;
        }

        .container1 {
            display: none;
        }

        .left,
        .right {
            width: 100%;
            justify-content: center;
        }

        .left a {
            width: auto;
        }

        #containerHeroe {
            flex-direction: column;
            height: auto;
            padding: 20px 20px;
            width: 100%;
        }

        .esqHeroe,
        .dirHeroe {
            width: 100%;
            text-align: center;
        }

        .dirHeroe img {
            position: static;
            height: auto;
            width: 80%;
        }

        #prodDestaques {
            flex-direction: column;
            align-items: center;
            justify-content: center;

        }

        .containerDestaques {
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            gap: 20px;
        }

        .feedback-carousel-container {
            max-width: 90%;
            max-height: 400px;
        }

        #featureSection {
            padding: 5vh;
        }

        #containerFeatures {
            flex-direction: column;
        }

        .featureBox p {
            width: 90%;
        }

        #cta h3 {
            text-align: center;
            font-size: 100%;
        }

        #cta p {
            text-align: center;
            font-size: 90%;
        }

        #containerFooter {
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }
    }
</style>

<body>
    <div class="container-fluid d-flex p-0 min-vh-100">

        <!-- Menu lateral -->
        <?php include '../includes/header-desktop-admin.php'; ?>
        <!-- Menu mobile -->
        <?php include '../includes/header-mobile-admin.php'; ?>

        <div class="flex-grow-1 p-4">
            <header class="dashboard-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="dashboard-title">Analytics Dashboard</h1>
                        <p class="dashboard-subtitle">Monitor your business performance</p>
                    </div>
                    <div class="col-md-4 d-flex justify-content-end align-items-center">
                        <button style="border: none; background: none; padding: 0; cursor: pointer; margin-right: 10px">
                            <svg width="34" height="36" viewBox="0 0 34 36" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g filter="url(#filter0_d_52_30)">
                                    <path
                                        d="M2 9C2 4.58172 5.58172 1 10 1H24C28.4183 1 32 4.58172 32 9V25C32 29.4183 28.4183 33 24 33H10C5.58172 33 2 29.4183 2 25V9Z"
                                        fill="white" />
                                    <path d="M10 7H24V27H10V7Z" stroke="#E5E7EB" />
                                    <g clip-path="url(#clip0_52_30)">
                                        <path
                                            d="M16.9993 9C16.4462 9 15.9993 9.44687 15.9993 10V10.5594C13.7337 10.9188 11.9993 12.8812 11.9993 15.25V16.2937C11.9993 17.7125 11.5149 19.0906 10.6306 20.1969L10.1649 20.7812C9.98368 21.0063 9.9493 21.3156 10.0743 21.575C10.1993 21.8344 10.4618 22 10.7493 22H23.2493C23.5368 22 23.7993 21.8344 23.9243 21.575C24.0493 21.3156 24.0149 21.0063 23.8337 20.7812L23.3681 20.2C22.4837 19.0906 21.9993 17.7125 21.9993 16.2937V15.25C21.9993 12.8812 20.2649 10.9188 17.9993 10.5594V10C17.9993 9.44687 17.5524 9 16.9993 9ZM16.9993 12H17.2493C19.0431 12 20.4993 13.4563 20.4993 15.25V16.2937C20.4993 17.7906 20.9337 19.25 21.7399 20.5H12.2587C13.0649 19.25 13.4993 17.7906 13.4993 16.2937V15.25C13.4993 13.4563 14.9556 12 16.7493 12H16.9993ZM18.9993 23H16.9993H14.9993C14.9993 23.5312 15.2087 24.0406 15.5837 24.4156C15.9587 24.7906 16.4681 25 16.9993 25C17.5306 25 18.0399 24.7906 18.4149 24.4156C18.7899 24.0406 18.9993 23.5312 18.9993 23Z"
                                            fill="#4B5563" />
                                    </g>
                                </g>
                                <defs>
                                    <filter id="filter0_d_52_30" x="0" y="0" width="34" height="36"
                                        filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                        <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                        <feColorMatrix in="SourceAlpha" type="matrix"
                                            values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
                                        <feOffset dy="1" />
                                        <feGaussianBlur stdDeviation="1" />
                                        <feColorMatrix type="matrix"
                                            values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.05 0" />
                                        <feBlend mode="normal" in2="BackgroundImageFix"
                                            result="effect1_dropShadow_52_30" />
                                        <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_52_30"
                                            result="shape" />
                                    </filter>
                                    <clipPath id="clip0_52_30">
                                        <path d="M10 9H24V25H10V9Z" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </button>
                        <img src="Frame.svg">
                        <div class="ms-3 mb-0">John Doe</div>
                    </div>
                </div>
            </header>
            <div class="container py-4">
                <div class="row g-3">

                    <!-- Total de Pedidos -->
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-title">Total Orders</div>
                            <div class="metric-value">1,284</div>
                            <div class="metric-change positive">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="me-1">
                                    <path d="M12 4L12 20M12 4L5 11M12 4L19 11" stroke="#28a745" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                +24.5%
                            </div>
                            <div class="comparison">vs. 1,031 last month</div>
                        </div>
                    </div>

                    <!-- Receita -->
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-title">Revenue</div>
                            <div class="metric-value">$86,429</div>
                            <div class="metric-change positive">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="me-1">
                                    <path d="M12 4L12 20M12 4L5 11M12 4L19 11" stroke="#28a745" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                +18.2%
                            </div>
                            <div class="comparison">vs. $73,121 last month</div>
                        </div>
                    </div>

                    <!-- Valor Médio do Pedido -->
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-title">Avg. Order Value</div>
                            <div class="metric-value">$67.31</div>
                            <div class="metric-change negative">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="me-1">
                                    <path d="M12 20L12 4M12 20L19 13M12 20L5 13" stroke="#dc3545" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                -2.3%
                            </div>
                            <div class="comparison">vs. $68.90 last month</div>
                        </div>
                    </div>

                    <!-- Clientes Ativos -->
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-title">Active Customers</div>
                            <div class="metric-value">892</div>
                            <div class="metric-change positive">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="me-1">
                                    <path d="M12 4L12 20M12 4L5 11M12 4L19 11" stroke="#28a745" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                +12
                            </div>
                            <div class="comparison">vs. 796 last month</div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="container py-4">
                <div class="row g-3">

                    <!-- Tendências -->
                    <div class="col-md-6">
                        <div class="metric-card-graph">
                            <div class="metric-title">Revenue Trends</div>
                            <br>
                            <div class="placeholder-graph">Revenue Trends Placeholder</div>
                        </div>
                    </div>
                    <!-- Estatísticas -->
                    <div class="col-md-6">
                        <div class="metric-card-graph">
                            <div class="metric-title">Order Statistics</div>
                            <br>
                            <div class="placeholder-graph">Order Statistics Placeholder</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container py-4">
                <div class="row g-3">

                    <!-- Tendências -->
                    <div class="col-md-8">
                        <div class="metric-card">
                            <div class="metric-title">Top Products</div>
                            <br>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Product</th>
                                        <th scope="col">Orders</th>
                                        <th scope="col">Revenue</th>
                                        <th scope="col">Growth</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td scope="row">Custom T-Shirts</td>
                                        <td>342</td>
                                        <td>$15,480</td>
                                        <td class="positive">+28%</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Business Cards</td>
                                        <td>256</td>
                                        <td>$12,800</td>
                                        <td class="positive">+15%</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Posters</td>
                                        <td>198</td>
                                        <td>$9,900</td>
                                        <td class="positive">+22%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <!-- Tipos de Consumidor -->
                    <div class="col-md-4">
                        <div class="metric-card-graph">
                            <div class="metric-title">Customer Segments</div>
                            <br>
                            <div class="placeholder-graph">Customer Segments Placeholder</div>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td scope="row">Business</td>
                                        <td>45%</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Individuals</td>
                                        <td>35%</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Reseller</td>
                                        <td>20%</td>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>
</body>

</html>