<?php $update_templates = version_compare( get_option( 'ninja_forms_version', '0' ), '3.0', '>' ); ?>
<?php if( $update_templates ): ?>
<script id="tmpl-nf-nobi-args-repeater-row" type="text/template">
    <div>
        <span class="dashicons dashicons-menu handle"></span>
    </div>
    <div>
        <label class="has-merge-tags">
            <input type="text" class="setting" value="{{{ data.value }}}" list="nb-value" data-id="value">
            <span class="dashicons dashicons-list-view merge-tags"></span>
            <datalist id="nb-value">
                {{{ data.renderOptions( 'value', data.value ) }}}
            </datalist>
        </label>
    </div>
    <div>
        <label class="has-merge-tags">
            <input type="text" class="setting" value="{{{ data.key }}}" data-id="key" list="nb-key">
            <span class="dashicons dashicons-list-view merge-tags"></span>
            <datalist id="nb-key">
                {{{ data.renderOptions( 'key', data.key ) }}}
            </datalist>
        </label>
    </div>
    <div>
        <span class="dashicons dashicons-dismiss nf-delete"></span>
    </div>
</script>
<?php else: ?>
<script id="tmpl-nf-nobi-args-repeater-row" type="text/template">
    <div>
        <span class="dashicons dashicons-menu handle"></span>
    </div>
    <div>
        <label class="has-merge-tags">
            <input type="text" class="setting" value="<%= value %>" list="nb-value" data-id="value">
            <datalist id="nb-value">
                {{{ data.renderOptions( 'value', data.value ) }}}
            </datalist>
            <span class="dashicons dashicons-list-view merge-tags"></span>
        </label>
    </div>
    <div>
        <label>
            <input type="text" class="setting" value="<%= key %>" data-id="key" list="nb-key">
            <span class="dashicons dashicons-list-view merge-tags"></span>
            <datalist id="nb-key">
                {{{ data.renderOptions( 'key', data.key ) }}}
            </datalist>
        </label>
    </div>
    <div>
        <span class="dashicons dashicons-dismiss nf-delete"></span>
    </div>
</script>
<?php endif; ?>
