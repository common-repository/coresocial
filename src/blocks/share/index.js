import {registerBlockType} from "@wordpress/blocks";
import {__} from "@wordpress/i18n";
import {BlockControls, useBlockProps} from "@wordpress/block-editor";
import {InspectorControls} from "@wordpress/editor";
import {CheckboxControl, Disabled, PanelBody, RangeControl, SelectControl, ToggleControl} from "@wordpress/components";
import {useEffect} from "@wordpress/element";
import {unescape as unescapeString, without} from "lodash";
import ServerSideRender from "@wordpress/server-side-render";
import icons from "../../helpers/icons";
import CoreSocialAlign from "../../controls/alignment";
import CoreSocialAdditional from "../../controls/additional";
import metadata from './block.json';

registerBlockType(metadata, {
    icon: icons.share,
    edit: ({attributes, setAttributes, context: {postId}, clientId}) => {
        useEffect(() => {
            const {block_id} = attributes;
            if (!block_id) {
                setAttributes({block_id: clientId.substring(0, 8)});
            }
        });

        const onNetworkChange = (id) => {
            const hasId = attributes.networks.includes(id);
            const newIds = hasId
                ? without(attributes.networks, id)
                : [...attributes.networks, id];
            setAttributes({networks: newIds})
        };

        return (
            <div {...useBlockProps()}>
                <Disabled>
                    <ServerSideRender
                        block='coresocial/share'
                        attributes={attributes}
                    />
                </Disabled>
                <BlockControls>
                    <CoreSocialAlign
                        attributes={attributes}
                        setAttributes={setAttributes}
                    ></CoreSocialAlign>
                </BlockControls>
                <InspectorControls key="settings">
                    <PanelBody title={__('Networks', 'coresocial')}>
                        {
                            coresocial_blocks.networks.map((network) => {
                                return (
                                    <div
                                        key={network.value}
                                    >
                                        <CheckboxControl
                                            checked={attributes.networks.length === 0 || attributes.networks.indexOf(network.value) !== -1}
                                            onChange={() => {
                                                onNetworkChange(network.value);
                                            }}
                                            label={unescapeString(network.label)}
                                        />
                                    </div>
                                )
                            })
                        }
                    </PanelBody>
                    <PanelBody initialOpen={false} title={__('Style', 'coresocial')}>
                        <SelectControl
                            label={__('Layout', 'coresocial')}
                            value={attributes.layout}
                            options={[
                                {label: __('Icon Only', 'coresocial'), value: 'icon'},
                                {label: __('Icon on the Left', 'coresocial'), value: 'left'},
                                {label: __('Icon on the Right', 'coresocial'), value: 'right'}
                            ]}
                            onChange={(value) => setAttributes({layout: value})}
                        />
                        <SelectControl
                            label={__('Color', 'coresocial')}
                            value={attributes.color}
                            options={[
                                {label: __('Filled Background', 'coresocial'), value: 'fill'},
                                {label: __('Plain Background', 'coresocial'), value: 'plain'}
                            ]}
                            onChange={(value) => setAttributes({color: value})}
                        />
                        <SelectControl
                            label={__('Style', 'coresocial')}
                            value={attributes.styling}
                            options={[
                                {label: __('Normal', 'coresocial'), value: 'normal'},
                                {label: __('Rounded', 'coresocial'), value: 'rounded'},
                                {label: __('Round', 'coresocial'), value: 'round'}
                            ]}
                            onChange={(value) => setAttributes({styling: value})}
                        />
                        {attributes.styling === 'rounded' && (
                            <RangeControl
                                label={__('Rounded Size', 'coresocial')}
                                value={attributes.rounded}
                                onChange={(value) => setAttributes({rounded: value})}
                                min={0}
                                max={100}
                                allowReset
                                resetFallbackValue={0}
                                step={1}
                                withInputField={true}
                                separatorType="none"
                                isShiftStepEnabled
                            />
                        )}
                        <RangeControl
                            label={__('Buttons Gap', 'coresocial')}
                            value={attributes.button_gap}
                            onChange={(value) => setAttributes({button_gap: value})}
                            min={0}
                            max={64}
                            allowReset
                            resetFallbackValue={0}
                            step={1}
                            withInputField={true}
                            separatorType="none"
                            isShiftStepEnabled
                        />
                        <RangeControl
                            label={__('Button Size', 'coresocial')}
                            value={attributes.button_size}
                            onChange={(value) => setAttributes({button_size: value})}
                            min={0}
                            max={256}
                            allowReset
                            resetFallbackValue={0}
                            step={1}
                            withInputField={true}
                            separatorType="none"
                            isShiftStepEnabled
                        />
                        <RangeControl
                            label={__('Font Size', 'coresocial')}
                            value={attributes.font_size}
                            onChange={(value) => setAttributes({font_size: value})}
                            min={0}
                            max={256}
                            allowReset
                            resetFallbackValue={0}
                            step={1}
                            withInputField={true}
                            separatorType="none"
                            isShiftStepEnabled
                        />
                    </PanelBody>
                    <PanelBody initialOpen={false} title={__('Counts', 'coresocial')}>
                        <ToggleControl
                            label={__('Show Counts', 'gd-knowledge-base')}
                            checked={attributes.share_count_active}
                            onChange={(value) => setAttributes({share_count_active: value})}
                        />
                        <ToggleControl
                            label={__('Hide if Zero', 'gd-knowledge-base')}
                            checked={attributes.share_count_hide_if_zero}
                            onChange={(value) => setAttributes({share_count_hide_if_zero: value})}
                        />
                    </PanelBody>
                    <CoreSocialAdditional
                        attributes={attributes}
                        setAttributes={setAttributes}
                        showContext={true}
                    ></CoreSocialAdditional>
                </InspectorControls>
            </div>
        )
    },
    save: () => {
        return null
    }
});
