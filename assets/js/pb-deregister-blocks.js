window.onload = function() {

  
        var blocks = [
            'core/paragraph',
            'core/image',
            //'core/columns',
            'core/gallery',
            'core/html',
            'core/list',
            'core/heading',
            'core/subhead',
            'core/quote',
            'core/audio',
            'core/file',
            'core/video',
            'core/table',
            'core/cover',
            'core/media-text',
            'core/more',
            'core/nextpage',
            'core/verse',
            'core/freeform',
            'core/preformatted',
            'core/pullquote',
            'core/button',
            'core/text-columns',
            'core/separator',
            'core/spacer',
            'core/shortcode',
            'core-embed/twitter',
            'core-embed/youtube',
            'core-embed/facebook',
            'core-embed/instagram',
            'core-embed/wordpress',
            'core-embed/spotify',
            'core-embed/flickr',
            'core-embed/vimeo',
            'core-embed/issuu',
            'core-embed/slideshare',
        ];
        if(document.getElementById("wpwrap")){
            setTimeout(function(){
                for(var i in blocks){
                    wp.blocks.unregisterBlockType(blocks[i]); 
                }
            },1000); 
        }
    }
