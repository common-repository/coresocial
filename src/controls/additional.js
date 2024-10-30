import {__} from "@wordpress/i18n";
import {PanelBody, SelectControl, TextControl} from "@wordpress/components";

const CoreSocialAdditional = ({attributes, setAttributes, showContext}) => {
    return (
        <PanelBody title={__('Additional', 'coresocial')} initialOpen={false}>
            {showContext && (
                <SelectControl
                    label={__('Page Context', 'coresocial')}
                    value={attributes.context}
                    options={[
                        {label: __('Global Context', 'coresocial'), value: 'auto'},
                        {label: __('Block Context', 'coresocial'), value: 'block'}
                    ]}
                    onChange={(value) => setAttributes({context: value})}
                />
            )}
            <TextControl
                label={__('CSS Class', 'coresocial')}
                value={attributes.class}
                onChange={(value) => setAttributes({class: value})}
            />
        </PanelBody>
    )
};

export default CoreSocialAdditional;
