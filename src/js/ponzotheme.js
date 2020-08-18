
import Flickity from 'Flickity'

const Ponzotheme = {
  init() {
      // empty slider array
      window.sliders = [];
      this.slideShows()
  },

  // ponzo sliders
  slideShows() {
       
    // destroy sliders
    for(var i in window.sliders){
        let slider = window.sliders[i]
        slider.destroy();
    }
  
    jQuery('.slider').each(function(index, value){
        const cellsMobile = jQuery(this).attr("data-mobile");
        const cellsDesktop = jQuery(this).attr('data-desktop');
        let cells = 1;
        if(window.innerWidth < 800){
            cells = parseInt(cellsMobile);
        }else{
            cells = parseInt(cellsDesktop)
        }
        if(cells){
            const flkty = new Flickity( this, {
                cellAlign: 'left',
                contain: true,
                groupCells: cells
            });
            window.sliders.push(flkty)
            // when browser is resized
            jQuery(this).removeClass('row');
        }else{
            // when browser is resized
            jQuery(this).addClass('row');
        }
      });
}
}
export default Ponzotheme
