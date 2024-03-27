if (!RedactorPlugins) var RedactorPlugins = {};

RedactorPlugins.scriptbuttons = function()
{
    return {
        init: function()
        {
            var sup = this.button.add('superscript', 'Superscript');
            //var sub = this.button.add('subscript', 'Subscript');
            //var span = this.button.add('spanscript', 'Spanscript');

            // make your added buttons as Font Awesome's icon
            this.button.setAwesome('superscript', 'fa-superscript');
            //this.button.setAwesome('subscript', 'fa-subscript');
            //this.button.setAwesome('spanscript', 'fa-flag-checkered');

            this.button.addCallback(sup, this.scriptbuttons.formatSup);
            //this.button.addCallback(sub, this.scriptbuttons.formatSub);
            //this.button.addCallback(span, this.scriptbuttons.formatSpan);
        },
        formatSup: function()
        {
            this.inline.format('sup');
        },
        formatSub: function()
        {
            this.inline.format('sub');
        },
        formatSpan: function()
        {
            this.inline.format('em');
        }
    };
};
