{assign var="return_url" value="`$config.http_location`/payments/mtpayment.php"}
<p style="display: none;">{$lang.text_mtpayment_notice|replace:"[return_url]":$return_url}</p>
<hr />

<div class="form-field">
    <label for="username">Username:</label>
    <input type="text" name="payment_data[processor_params][username]" id="username" value="{$processor_params.username}" class="input-text" size="255" />
</div>

<div class="form-field">
    <label for="secret_key">Secret key:</label>
    <input type="text" name="payment_data[processor_params][secret_key]" id="secret_key" value="{$processor_params.secret_key}" class="input-text" size="255" />
</div>
