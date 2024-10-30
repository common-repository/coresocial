import {registerBlockType} from "@wordpress/blocks";
import {__} from "@wordpress/i18n";
import {BlockControls, ColorPaletteControl, useBlockProps} from "@wordpress/block-editor";
import {InspectorControls} from "@wordpress/editor";
import {CheckboxControl, Disabled, PanelBody, RangeControl, SelectControl, TextControl} from "@wordpress/components";
import {useEffect} from "@wordpress/element";
import {unescape as unescapeString, without} from "lodash";
import ServerSideRender from "@wordpress/server-side-render";
import icons from "../../helpers/icons";
import CoreSocialAlign from "../../controls/alignment";
import CoreSocialAdditional from "../../controls/additional";
import metadata from './block.json';

registerBlockType(metadata, {
    icon: icons.profiles,
    edit: ({attributes, setAttributes, clientId}) => {
        useEffect(() => {
            const {block_id} = attributes;
            if (!block_id) {
                setAttributes({block_id: clientId.substring(0, 8)});
            }
        });

        const colorControl = [
            {label: __('Inherit', 'coresocial'), value: 'inherit'},
            {label: __('Transparent', 'coresocial'), value: 'transparent'},
            {label: __('Brand Color', 'coresocial'), value: 'brand'},
            {label: __('Custom Color', 'coresocial'), value: 'custom'}
        ];

        const displayControl = [
            {label: __('Icon Only', 'coresocial'), value: 'icon'},
            {label: __('Label Only', 'coresocial'), value: 'label'},
            {label: __('Label with Top Icon', 'coresocial'), value: 'icon-top'},
            {label: __('Label with Left Icon', 'coresocial'), value: 'icon-left'},
            {label: __('Label with Right Icon', 'coresocial'), value: 'icon-right'}
        ];

        const displayStyle = [
            {label: __('Plain', 'coresocial'), value: 'plain'},
            {label: __('Shaded', 'coresocial'), value: 'shaded'}
        ];

        const displayLabel = [
            {label: __('Name', 'coresocial'), value: 'name'},
            {label: __('Name & Followers', 'coresocial'), value: 'name-followers'},
            {label: __('Followers', 'coresocial'), value: 'followers'}
        ];

        const itemAlign = [
            {label: __('Left', 'coresocial'), value: 'left'},
            {label: __('Center', 'coresocial'), value: 'center'},
            {label: __('Right', 'coresocial'), value: 'right'}
        ];

        const onProfileChange = (id) => {
            const hasId = attributes.profiles.includes(id);
            const newIds = hasId
                ? without(attributes.profiles, id)
                : [...attributes.profiles, id];
            setAttributes({profiles: newIds})
        };

        return (
            <div {...useBlockProps()}>
                <Disabled>
                    <ServerSideRender
                        block='coresocial/profiles'
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
                    <PanelBody title={__('Profiles', 'coresocial')}>
                        <SelectControl
                            label={__('Profiles to show', 'coresocial')}
                            value={attributes.show}
                            options={[
                                {label: __('All Profiles', 'coresocial'), value: 'all'},
                                {label: __('Select Profiles', 'coresocial'), value: 'profiles'}
                            ]}
                            onChange={(value) => setAttributes({show: value})}
                        />
                        {attributes.show === 'profiles' && (
                            coresocial_blocks.profiles.map((profile) => {
                                return (
                                    <div
                                        key={profile.value}
                                    >
                                        <CheckboxControl
                                            checked={attributes.profiles.indexOf(profile.value) !== -1}
                                            onChange={() => {
                                                const id = parseInt(profile.value);
                                                onProfileChange(id);
                                            }}
                                            label={unescapeString(profile.label)}
                                        />
                                    </div>
                                )
                            })
                        )}
                    </PanelBody>
                    <PanelBody initialOpen={true} title={__('Display', 'coresocial')}>
                        <SelectControl
                            label={__('Layout', 'coresocial')}
                            value={attributes.layout}
                            options={displayControl}
                            onChange={(value) => setAttributes({layout: value})}
                        />
                        <SelectControl
                            label={__('Style', 'coresocial')}
                            value={attributes.style}
                            options={displayStyle}
                            onChange={(value) => setAttributes({style: value})}
                        />
                        <SelectControl
                            label={__('Label', 'coresocial')}
                            value={attributes.label}
                            options={displayLabel}
                            onChange={(value) => setAttributes({label: value})}
                        />
                        {attributes.style === 'plain' && (
                            <SelectControl
                                label={__('Item Alignment', 'coresocial')}
                                value={attributes.item_align}
                                options={itemAlign}
                                onChange={(value) => setAttributes({item_align: value})}
                            />
                        )}
                        {attributes.align === 'justify' && (
                            <RangeControl
                                label={__('Columns', 'coresocial')}
                                value={attributes.columns}
                                onChange={(value) => setAttributes({columns: value})}
                                min={0}
                                max={4}
                                allowReset
                                resetFallbackValue={0}
                                step={1}
                                withInputField={true}
                                separatorType="none"
                                isShiftStepEnabled
                            />
                        )}
                    </PanelBody>
                    <PanelBody initialOpen={false} title={__('Sizes', 'coresocial')}>
                        <RangeControl
                            label={__('Icon Size', 'coresocial')}
                            value={attributes.icon_size}
                            onChange={(value) => setAttributes({icon_size: value})}
                            min={0}
                            max={96}
                            allowReset
                            resetFallbackValue={20}
                            step={1}
                            withInputField={true}
                            separatorType="none"
                            isShiftStepEnabled
                        />
                        <RangeControl
                            label={__('Label Font Size', 'coresocial')}
                            value={attributes.font_size}
                            onChange={(value) => setAttributes({font_size: value})}
                            min={0}
                            max={96}
                            allowReset
                            resetFallbackValue={16}
                            step={1}
                            withInputField={true}
                            separatorType="none"
                            isShiftStepEnabled
                        />
                        <RangeControl
                            label={__('Icon Border Radius', 'coresocial')}
                            value={attributes.icon_border_radius}
                            onChange={(value) => setAttributes({icon_border_radius: value})}
                            min={0}
                            max={96}
                            allowReset
                            resetFallbackValue={0}
                            step={1}
                            withInputField={true}
                            separatorType="none"
                            isShiftStepEnabled
                        />
                    </PanelBody>
                    <PanelBody initialOpen={false} title={__('Colors', 'coresocial')}>
                        <SelectControl
                            label={__('Icon', 'coresocial')}
                            value={attributes.icon_text}
                            options={colorControl}
                            onChange={(value) => setAttributes({icon_text: value})}
                        />
                        {attributes.icon_text === 'custom' && (
                            <ColorPaletteControl
                                label={__('Icon Custom Color', 'coresocial')}
                                value={attributes.icon_text_custom}
                                onChange={(value) => setAttributes({icon_text_custom: value})}
                            />
                        )}
                        <SelectControl
                            label={__('Background', 'coresocial')}
                            value={attributes.icon_background}
                            options={colorControl}
                            onChange={(value) => setAttributes({icon_background: value})}
                        />
                        {attributes.icon_background === 'custom' && (
                            <ColorPaletteControl
                                label={__('Background Custom Color', 'coresocial')}
                                value={attributes.icon_background_custom}
                                onChange={(value) => setAttributes({icon_background_custom: value})}
                            />
                        )}
                        <SelectControl
                            label={__('Icon on Hover', 'coresocial')}
                            value={attributes.icon_hover_text}
                            options={colorControl}
                            onChange={(value) => setAttributes({icon_hover_text: value})}
                        />
                        {attributes.icon_hover_text === 'custom' && (
                            <ColorPaletteControl
                                label={__('Icon on hover Custom Color', 'coresocial')}
                                value={attributes.icon_hover_text_custom}
                                onChange={(value) => setAttributes({icon_hover_text_custom: value})}
                            />
                        )}
                        <SelectControl
                            label={__('Background on Hover', 'coresocial')}
                            value={attributes.icon_hover_background}
                            options={colorControl}
                            onChange={(value) => setAttributes({icon_hover_background: value})}
                        />
                        {attributes.icon_hover_background === 'custom' && (
                            <ColorPaletteControl
                                label={__('Background on hover Custom Color', 'coresocial')}
                                value={attributes.icon_hover_background_custom}
                                onChange={(value) => setAttributes({icon_hover_background_custom: value})}
                            />
                        )}
                    </PanelBody>
                    <PanelBody initialOpen={false} title={__('Links', 'coresocial')}>
                        <TextControl
                            label={__("Target", "coresocial")}
                            value={attributes.target}
                            onChange={(value) => setAttributes({target: value})}
                        />
                        <TextControl
                            label={__("REL", "coresocial")}
                            value={attributes.rel}
                            onChange={(value) => setAttributes({rel: value})}
                        />
                    </PanelBody>
                    <CoreSocialAdditional
                        attributes={attributes}
                        setAttributes={setAttributes}
                        showContext={false}
                    ></CoreSocialAdditional>
                </InspectorControls>
            </div>
        )
    },
    save: () => {
        return null
    }
});
