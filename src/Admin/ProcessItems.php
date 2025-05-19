<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orders- Print & Go</title>
  <link rel="stylesheet" href="css/processItems.css">
  <script src="js/processItems.js"></script>
</head>

<body>
  <div id="container">
    <div class="header">
      <div class="header-l">
        <h2>Order Information</h2>
        <span>Order Number: #HF</span>
      </div>
      <div class="header-r">
        <div>
          <p>Not Processed</p>
        </div>
        <p>Order Source: Print&Go</p>
      </div>
    </div>
    <hr>
    <div class="order-details">
      <div class="order-detail-l">
        <h3>Order Details</h3>
        <div>
          <label for="Customer" class="label">Customer</label>
          <p class="nome-cliente">Camila</p>
        </div>
        <div>
          <label for="Product" class="label">Product</label>
          <p class="nome-produto">T-Shirt</p>
        </div>
        <div>
          <label for="Size" class="label">Size</label>
          <p class="tamanho-produto">2XL</p>
        </div>
      </div>
      <div class="order-detail-r">
        <div>
          <label for="Personalization" class="label">Personalization</label>
          <p class="personalizacao">NO PERSONALIZATION</p>
        </div>
        <div>
          <label for="Weight" class="label">Weight</label>
          <p class="peso-produto">200 g</p>
        </div>
        <div>
          <label for="Quantity" class="label">Quantity</label>
          <p class="quantidade-produto">1</p>
        </div>
      </div>
    </div>
    <div class="tracking-info">
      <h3>Tracking Information</h3>
      <p>Adding a tracking number will help improve customer satisfaction and reduce support inquiries.</p>
      <div>
        <div class="tracking-number">
          <label for="Tracking Number">Tracking Number</label>
          <input type="text" class="input" placeholder="Enter tracking number">
        </div>
        <div class="carrier">
          <label for="Carrier">Carrier</label>
          <input type="text" class="input" placeholder="Enter carrier name">
        </div>

      </div>
      <button class="add-tracking"> + Add another tracking number</button>
    </div>
    <div class="shipping-address">
      <h3>Shipping Address</h3>
      <div class="container-envio">
        <h4 class="nome-envio"></h4>
        <p class="morada-envio"></p>
        <span class="span-shipping">Phone: </span>
        <span class="telemovel"></span>
        <span class="span-shipping">Chosen Shipping Method:</span>
        <span class="metodo-envio"></span>
      </div>
    </div>
    <hr>
    <div class="footer-container">
      <div>
        <p>Processing from: Print&Go</p>
        <span>Items to process:</span><span class="itens-processar"></span>
      </div>
      <div>
        <input type="checkbox" class="notification" value="Send!"> <label for="Send Notification to Customer">Send Notification to Customer</label>
      </div>
    </div>
    <div class="buttons-container">
      <button type="submit" class="process-order">Process Order</button>
      <button type="button" class="print">
        <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M4.51562 0C3.4125 0 2.51562 0.896875 2.51562 2V5H4.51562V2H11.6L12.5156 2.91563V5H14.5156V2.91563C14.5156 2.38438 14.3062 1.875 13.9312 1.5L13.0156 0.584375C12.6406 0.209375 12.1313 0 11.6 0H4.51562ZM12.5156 11V12V14H4.51562V12V11.5V11H12.5156ZM14.5156 12H15.5156C16.0688 12 16.5156 11.5531 16.5156 11V8C16.5156 6.89687 15.6187 6 14.5156 6H2.51562C1.4125 6 0.515625 6.89687 0.515625 8V11C0.515625 11.5531 0.9625 12 1.51562 12H2.51562V14C2.51562 15.1031 3.4125 16 4.51562 16H12.5156C13.6187 16 14.5156 15.1031 14.5156 14V12ZM14.0156 7.75C14.2145 7.75 14.4053 7.82902 14.546 7.96967C14.6866 8.11032 14.7656 8.30109 14.7656 8.5C14.7656 8.69891 14.6866 8.88968 14.546 9.03033C14.4053 9.17098 14.2145 9.25 14.0156 9.25C13.8167 9.25 13.6259 9.17098 13.4853 9.03033C13.3446 8.88968 13.2656 8.69891 13.2656 8.5C13.2656 8.30109 13.3446 8.11032 13.4853 7.96967C13.6259 7.82902 13.8167 7.75 14.0156 7.75Z" fill="#1F2937" />
        </svg>
        Print Delivery Label
      </button>
    </div>

  </div>


</body>

</html>