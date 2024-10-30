import {alignCenter, alignJustify, alignLeft, alignNone, alignRight} from "@wordpress/icons";
import {__} from "@wordpress/i18n";
import {AlignmentToolbar} from "@wordpress/block-editor";

const CoreSocialAlign = ({attributes, setAttributes}) => {
    return (
        <AlignmentToolbar
            alignmentControls={[
                {icon: alignNone, title: __('No align', 'coresocial'), align: 'none'},
                {icon: alignLeft, title: __('Align left', 'coresocial'), align: 'left'},
                {icon: alignCenter, title: __('Align center', 'coresocial'), align: 'center'},
                {icon: alignRight, title: __('Align right', 'coresocial'), align: 'right'},
                {icon: alignJustify, title: __('Justify', 'coresocial'), align: 'justify'}
            ]}
            value={attributes.align}
            onChange={(value) => setAttributes({align: value})}
        />
    )
};

export default CoreSocialAlign;
