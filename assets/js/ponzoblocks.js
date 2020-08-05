;(function($) {
  //on ready
  $(document).ready(function() {
    //init builder within dashboard (wpwrap)
    if (document.getElementById('wpwrap')) {
      //when preview is clicked in block
      $(document).on('click', "[aria-label='Switch to Preview']", function(e) {
        pbInitMode('Preview')
      })
      //when edit is clicked in block
      $(document).on('click', "[aria-label='Switch to Edit']", function(e) {
        pbInitMode('Edit')
      })

    }
    //create promise to check when wp-block is found
    var promise = new Promise(function(resolve, reject) {
      var checker = setInterval(function() {
        if ($('.wp-block').length) {
          resolve()
          clearInterval(checker)
        }
      }, 100)
    })
    //init block creation modes
    promise.then(function() {
      pbInitMode('Edit')
      pbInitMode('Preview')
    })
    if (window.acf) {
      window.acf.addAction('remove', function($el) {
        var block = $($el).closest('.wp-block')
        setTimeout(function() {
          pbCreateTabnav(block, true)
        }, 1000)
      })
      window.acf.addAction('append', function($el) {
        var block = $($el).closest('.wp-block')
        setTimeout(function() {
          pbCreateTabnav(block, true)
        }, 50)
      })
    }
  })

  function pbInitMode(mode) {
    $('.wp-block').each(function() {
      pbTriggerMode(this, mode)
    })
  }

  function pbTriggerMode(e, mode) {
    let pbBlock = $(e.target).closest('.wp-block')
    let type = null
    //if no pb block is found
    if (!pbBlock.length) {
      //if e is the wp-block
      if ($(e).hasClass('wp-block')) {
        pbBlock = $(e)
      }
    }
    
    if (pbBlock.length) {
      type = pbBlock.attr('data-type')
      if(mode == 'Preview'){
        pbBlock.addClass('ponzo')
      }else{
        pbBlock.removeClass('ponzo')
      }
    }
   
    //create promise to interval
    var promise = new Promise(function(resolve, reject) {
      var checker = setInterval(function() {
        var pbFoundBlock = pbContentReady(mode, pbBlock)
        if (pbFoundBlock) {
          resolve({ type: type, mode: mode, block: pbBlock })
          clearInterval(checker)
        }
      }, 100)
    })
    promise.then(function(resolveObj) {
      pbSwitch(resolveObj.type, resolveObj.mode, resolveObj.block)
    })
  }
  //find block by inner class
  function pbContentReady(mode, pbBlock) {
    let findmodeClass = '.acf-block-preview'
    if (mode === 'Edit') {
      findmodeClass = '.acf-block-fields'
    }
    return $(pbBlock).find(findmodeClass).length
  }
  //find block by inner class
  function pbSwitch(type, mode, block) {
    let callbackName = null
    if (type === 'acf/contentcolumns') {
      callbackName = 'Contentcolumns'
    } else if (type === 'acf/testimonials') {
      callbackName = 'Testimonials'
    }
    //if function is needed
    if (callbackName) {
      let callback = 'pb' + callbackName + mode
      let method = eval('(' + callback + ')')
      method(block)
    }
  }
  function pbTestimonialsEdit() {}
  function pbTestimonialsPreview() {
    window.initTheme()
  }
  function pbContentcolumnsPreview(block) {}
  //custom tab editor
  function pbContentcolumnsEdit(block) {
    //create nav
    pbCreateTabnav(block, false)
    pbRepeaterHelpers(block)
  }

  function pbRepeaterHelpers(block) {
    //expand rows
    $(block)
      .find('.tab-expand')
      .unbind('mouseup')
      .mouseup(function() {
        let toggle = $(this)
        let parent = toggle.closest('.acf-tab-repeater')
        let expand = toggle.attr('data-expand')
        if (expand === 'false') {
          $('.acf-row', parent).addClass('acf-tabrepeater-active')
          toggle.attr('data-expand', 'true')
          toggle.text('collapse')
        } else {
          $('.acf-row', parent).removeClass('acf-tabrepeater-active')
          toggle.attr('data-expand', 'false')
          toggle.text('expand')
        }
      })
  }
  function pbCreateTabnav(block, lastnav) {
    //find label element within repeater
    var container = $(block)
      .find('.acf-tab-repeater')
      .find('.acf-label')
      .first()
    //empty first
    $(container)
      .find('.acf-tabrepeater-nav')
      .remove()
    //empty first
    $(container)
      .find('.tab-expand')
      .remove()
    //add expand button
    var expand = $(
      '<a href="#" class="tab-expand" data-expand="false">expand</a>'
    ).appendTo(container)
    //append menu
    var menu = $('<ul class="acf-tabrepeater-nav"></ul>').appendTo(container)
    var index = 1
    var first = null
    var last = null
    var firstLi = null
    var lastLi = null
    //loop each row
    $('.acf-row', block).each(function() {
      var row = this
      if (index == 1) {
        first = row
      }
      //pure rows
      if (!$(row).hasClass('acf-clone')) {
        var liItem = '<li>Block ' + index + '</li>'
        var navItem = $(liItem).appendTo(menu)
        //add click handler
        navItem.click(function() {
          // add active class
          pbRemoveActive(block, menu)
          pbAddActive(this, row)
        })
        if (index == 1) {
          firstLi = navItem
        }
        lastLi = navItem
        index++
        //set last
        last = row
      }
    })

    //highlight first
    if (!lastnav) {
      if (first) {
        pbRemoveActive(block, menu)
        pbAddActive(firstLi, first)
      }
    } else {
      //hightlight last
      if (lastLi) {
        pbRemoveActive(block, menu)
        pbAddActive(lastLi, last)
      }
    }
    //set helpers again
    pbRepeaterHelpers(block)
  }
  function pbRemoveActive(block, menu) {
    // remove first
    $(block)
      .find('.acf-row')
      .removeClass('acf-tabrepeater-active')
    $(menu)
      .find('li')
      .removeClass('acf-tabrepeater-active')
  }
  function pbAddActive(litem, row) {
    $(litem).addClass('acf-tabrepeater-active')
    $(row).addClass('acf-tabrepeater-active')
  }
})(jQuery)
