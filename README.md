Ys_ShipmentComment — Magento 2 Extension

Adds shipment comments to checkout and orders.

Overview:
Ys_ShipmentComment extends Magento 2 checkout and order flow by allowing customers to leave a shipment/delivery comment during checkout.
The comment is stored on the quote and transferred to the order when placed.
It is visible in:
- Admin Order View,
- Customer Account Order View
- optionally included in order confirmation emails ({{var shipment_comment}}).
- The module supports both Luma and headless setups (GraphQL mutation included).

✨ Features
Feature	Description
- Adds “Shipment Comment” textarea under the shipping step
- Admin can enable/disable feature globally
- Show only for selected shipping methods
- Comment can be optional or required depending on admin toggle
- Real-time validation + error message (no alert popups)
- Comment is displayed on order view pages
- Separate CSS configuration for checkout and customer account
- Supports GraphQL: setShipmentCommentOnCart mutation + shipment_comment query field
- Additional server-side validation


Installation:
unzip Ys_ShipmentComment.zip -d app/code
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:flush
bin/magento setup:static-content:deploy -f


Configuration:
Stores → Configuration → Sales → Shipping Settings → Shipment Comment


Setting	Description:
Enable Shipment Comment	Turns the feature on/off globally.
Require Shipment Comment	When set to Yes, the comment field becomes required for selected methods. When No, it’s optional.
Selected Methods	Choose which shipping methods show the comment field (e.g., flatrate_flatrate). If a method is not selected, the field won’t appear at all.
Checkout CSS	Custom CSS applied to checkout page only.
Customer Account CSS	Custom CSS applied to the customer order view page.


How It Works:
1. Rendering at Checkout

During checkout layout generation, a LayoutProcessor plugin injects a new UI component:
shipping-step > shippingAddress > shippingAdditional > ys-shipment-comment

2. Config propagation

Dedicated ConfigProvider exports settings into checkoutConfig object, for example:
{
  enabled: true,
  requireEnabled: true,
  requiredMethods: ["flatrate_flatrate"] <= array of selected methods in config
}

3. Frontend logic (shipment-comment.js)

Reads the above config and observes the selected shipping method.

Dynamically toggles:
isVisible(true/false) — field shown only for selected methods.
isRequired(true/false) — required if “Require Shipment Comment” = Yes.
Adds inline error message when validation fails.
Uses a RequireJS mixin to inject the comment into the REST payload.
The field’s current value is also mirrored into quote.shippingAddress.extension_attributes.shipment_comment for compatibility.

4. Server-side validation

Plugin on Magento\Checkout\Model\ShippingInformationManagement intercepts
saveAddressInformation():
- Reads the comment from extension_attributes.
- Checks if the selected method requires comment.
- Saves comment to quote.shipment_comment or throws an error if value is required but missing.

5. Order submission

Observer (sales_model_service_quote_submit_before) copies quote.shipment_comment
to the sales_order.shipment_comment field.

6. Admin and customer display

Admin Order View: Displays the comment under order information.
Customer Account Order View: Displays comment in a new “Shipment Comment” block.
Both pages support inline styling via the CSS config values.

7. Email integration

Plugin on Magento\Sales\Model\Order\Email\SenderBuilder adds
{{var shipment_comment}} so that it can be used in email templates.


GraphQL Support:
The module exposes a read and write API for headless stores.

Query example
{
  cart(cart_id: "abcdef123456") {
    shipment_comment
  }
}

Mutation example
mutation {
  setShipmentCommentOnCart(
    input: { cart_id: "abcdef123456", shipment_comment: "Please deliver before 5 PM." }
  ) {
    cart {
      shipment_comment
    }
  }
}

Resolvers handle both read (CartShipmentComment, OrderShipmentComment) and write (SetShipmentCommentOnCart).