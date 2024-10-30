const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { SelectControl } = wp.components;

registerBlockType( 'rokibcontactum/form', {
    title: __( 'ContactumForm', '' ),
    description: __( '', ''),
    icon: '',
    category: 'formatting',
    keywords: [
        __('Contactum Form'),
        __('Forms'),
        __('Advanced Forms'),
    ],
    attributes: {
        formId: {
            type: 'string'
        }
    },
    edit: function( { attributes, setAttributes } ) {
        const config = window.contactumBlock;
        return (
            <SelectControl
                label={__("Select a Form")}
                value={attributes.formId}
                options={config.forms.map(form => ({
                    value: form.value,
                    label: form.text
                }))}
                onChange={formId => setAttributes({formId})}
            />
        )
    },
    save: function( { attributes } ) {
        return '[contactum id="' + attributes.formId + '"]'
    },
});
