<div class="product-accordion mb-3">

    <!-- Description -->
    <div class="product-box">
        <div class="product-header" data-toggle="collapse" data-target="#descCollapse">
            <span class="fs-15 fw-700 text-dark">Description</span>
            <i class="toggle-icon las la-angle-up text-muted"></i>
        </div>

        <div id="descCollapse" class="collapse show">
            <div class="product-body aiz-editor-data">
                {!! $detailedProduct->getTranslation('description') !!}
            </div>
        </div>
    </div>

    <!-- Shipping -->
<div class="product-box">
    <div class="product-header" data-toggle="collapse" data-target="#shipCollapse">
        <span class="fs-15 fw-700 text-dark">Shipping Description</span>
        <i class="toggle-icon las la-angle-up text-muted"></i>
    </div>

    <div id="shipCollapse" class="collapse">
        <div class="product-body aiz-editor-data">

            <p>
                We ship across India. We also provide <strong>Cash on Delivery</strong> payment option**.
            </p>

            <p style="font-style: italic; color:#8a6d3b;">
                Please refer to our shipping table below:
            </p>

            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse: collapse; text-align:left;">
                    <thead>
                        <tr style="background:#f5f5f5;">
                            <th style="padding:10px; border:1px solid #ddd;">Order Total</th>
                            <th style="padding:10px; border:1px solid #ddd;">Shipping Charge</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding:10px; border:1px solid #ddd;">
                                Below ₹500 (Prepaid)
                            </td>
                            <td style="padding:10px; border:1px solid #ddd;">
                                ₹99
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:10px; border:1px solid #ddd;">
                                ₹500 and above (Prepaid)
                            </td>
                            <td style="padding:10px; border:1px solid #ddd;">
                                <strong>FREE!</strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:10px; border:1px solid #ddd;">
                                ₹700 and above (Cash on Delivery)
                            </td>
                            <td style="padding:10px; border:1px solid #ddd;">
                                ₹40
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <p style="margin-top:12px;">
                <a href="https://axvero.com/return-policy" target="_blank" style="font-weight:600;color:blue">
                    👁 Read More
                </a>
            </p>

        </div>
    </div>
</div>

    <!-- Return -->
  <div class="product-box">
    <div class="product-header" data-toggle="collapse" data-target="#returnCollapse">
        <span class="fs-15 fw-700 text-dark">Return Description</span>
        <i class="toggle-icon las la-angle-up text-muted"></i>
    </div>

    <div id="returnCollapse" class="collapse">
        <div class="product-body aiz-editor-data">

            <p>
                Products are eligible for return within <strong>24 hours</strong> of delivery 
                (extended to <strong>7 days</strong> for Diamond Tier members).
            </p>

            <p>
                Please note that <strong>custom-made orders are not eligible for return.</strong>
            </p>

            <p>
                To ensure a successful return, the product must have its original tags attached and intact. 
                If the original tags are missing, axvero reserves the right to decline the return request, 
                and the product may be sent back to the customer.
            </p>

            <p>
                <strong>Return handling charges will be applicable.</strong>
            </p>

            <hr>

            <h5 style="font-weight:700; font-size:18px;">
                Open Box Delivery – No Return Policy
            </h5>

            <p>
                Under the Open Box Delivery process, the customer is required to inspect the product 
                at the time of delivery in the presence of the delivery personnel.
            </p>

            <p>
                Once the product is accepted after inspection, no return, replacement, or exchange 
                shall be entertained thereafter.
            </p>

            <p>
                Any damage, defect, or incorrect item must be reported and rejected 
                at the time of delivery.
            </p>

            <p style="margin-top:10px;">
                <a href="https://axvero.com/return-policy" target="_blank" style="font-weight:600;color:blue">
                    👁 Read More
                </a>
            </p>

        </div>
    </div>
</div>

</div>


<style>
.product-accordion {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.product-box {
    width: 100%;
    border-radius: 14px;
    border: 1px solid #e8e8ee;
    background: #ffffff;
    overflow: hidden;
    transition: box-shadow 0.25s ease;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
}

.product-box:hover {
    box-shadow: 0 4px 14px rgba(0,0,0,0.09);
}

.product-header {
    padding: 16px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    color: #222;
    background: #fff;
    border-radius: 14px;
}

.product-box .collapse.show ~ .product-header,
.product-header[aria-expanded="true"] {
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
}

.product-body {
    padding: 0 20px 18px 20px;
    background: #fff;
}

.toggle-icon {
    font-style: normal;
    font-size: 14px;
    transition: transform 0.3s ease;
    color: #888;
}

.product-box.active .toggle-icon {
    transform: rotate(180deg);
}

@media (max-width: 1199.98px) {
    .product-accordion {
        gap: 10px;
    }
    .product-box {
        border-radius: 12px;
        background: #fff;
        border: 1px solid #e8e8ee;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    .product-header {
        padding: 14px 16px;
        background: #fff;
        border-radius: 12px;
    }
    .product-body {
        padding: 0 16px 14px 16px;
        background: #fff;
    }
}
</style>

<script>
document.querySelectorAll('.product-header').forEach(header => {

    header.addEventListener('click', function () {

        let box = this.closest('.product-box');
        let target = document.querySelector(this.getAttribute('data-target'));

        setTimeout(() => {
            if (target.classList.contains('show')) {
                box.classList.add('active');
            } else {
                box.classList.remove('active');
            }
        }, 200);

    });

});
</script>