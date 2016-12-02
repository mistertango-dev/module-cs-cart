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

{assign var="statuses" value=$smarty.const.STATUSES_ORDER|fn_get_statuses:true}

<div class="form-field">
    <label for="status_pending">Status pending:</label>
    <select name="payment_data[processor_params][status_pending]" id="status_pending">
        {foreach from=$statuses item="s" key="k"}
        <option value="{$k}" {if $processor_params.status_pending == $k || !$processor_params.status_pending && $k == 'O'}selected="selected"{/if}>{$s}</option>
        {/foreach}
    </select>
</div>

<div class="form-field">
    <label for="status_paid">Status paid:</label>
    <select name="payment_data[processor_params][status_paid]" id="status_paid">
        {foreach from=$statuses item="s" key="k"}
        <option value="{$k}" {if $processor_params.status_paid == $k || !$processor_params.status_paid && $k == 'O'}selected="selected"{/if}>{$s}</option>
        {/foreach}
    </select>
</div>

<div class="form-field">
    <label for="override_callback_url">Override callback url?</label>
    <input type="checkbox" name="payment_data[processor_params][override_callback_url]" id="override_callback_url" value="1"{if $processor_params.override_callback_url == 1} checked="checked"{/if} />
</div>

<div class="form-field">
    <label for="callback_url">Callback url:</label>
    <input type="text" name="payment_data[processor_params][callback_url]" id="callback_url" value="{$processor_params.callback_url}" class="input-text" size="255" />
</div>
