/**
 * Load init function when the page is ready
 *
 * @since 1.8.0
 */
jQuery( document ).ready( init );

function init() {
    showMultipleConfig();
    selectRegion();
    toggleRegionSelector();
    addBanner();
    removeBanner();
}

function showMultipleConfig() {
    jQuery('#multiple-config').on('change', function(){
        if(jQuery(this).is(':checked')){
            jQuery('.cb-multiple__container').removeClass('hidden');
        }else{
            jQuery('.cb-multiple__container').addClass('hidden');
        }
    });
}

function toggleRegionSelector() {
    const initialValues = jQuery('form').serialize();
    let submitBtn = jQuery('p.submit #submit');
    jQuery(document).on('click','.cb-region__region__selector', function(){
        jQuery('.cb-region__region__list').each(function(){
            if(!jQuery(this).hasClass('hidden')){
                jQuery(this).addClass('hidden');
            }
        });
        jQuery(this).next('.cb-region__region__list').removeClass('hidden');
    });

    jQuery(document).on('click','.cb-region__veil', function(){
        jQuery(this).closest('.cb-region__region__list').addClass('hidden');
        let newValues = jQuery('form').serialize();
        if(newValues !== initialValues) {
            submitBtn.addClass('enabled');
        }else{
            submitBtn.removeClass('enabled');
        }
    });
}

function selectRegion() {
    jQuery(document).on('click','.cb-region__region__item', function(){
        let parent = jQuery(this).closest('.cb-region__table__item');
        let code = jQuery(this).data('region');
        let name = jQuery(this).text();
        if(jQuery(this).hasClass('selected-region')){
            jQuery(this).removeClass('selected-region');
            toggleCode(code,name,parent);
        }else{
            jQuery(this).addClass('selected-region');
            toggleCode(code,name,parent);
        }
    });
}

function toggleCode(code,name,parent) {
    const regionInput = jQuery('.second-banner-regions',parent);
    const regionVal = jQuery('.second-banner-regions',parent).val();
    const ccpaInput = jQuery('#ccpa-compatibility');
    const submitBtn = jQuery('p.submit #submit');
    const selectedBox = jQuery('.selected-regions',parent);
    const allRegionInputs = jQuery('.second-banner-regions');
    let regionList = regionVal.split(', ');

    if(regionList[0]==='')
        regionList = [];

    let ccpaExists = false;

    if(regionList.indexOf(code)!==-1){
        regionList.splice(regionList.indexOf(code),1);
    }else{
        regionList.push(code);
    }

    let itemSelector = '#'+code;
    let selected = jQuery(itemSelector,parent);

    if(selected.length<=0){
        let newItem = document.createElement('div');
        newItem.classList.add('selected-regions-item');
        newItem.id = code;
        newItem.innerText = name;
        selectedBox.append(newItem);
    }else{
        selected.remove();
    }

    const newRegions = regionList.join(', ');
    if(newRegions.length<=0){
        jQuery('.default-none',parent).removeClass('hidden');
    }else{
        jQuery('.default-none',parent).addClass('hidden');
    }
    regionInput.val(newRegions);

    allRegionInputs.each(function(){
        const inputValue = jQuery(this).val();
        if(inputValue.indexOf('US-06')!==-1){
            ccpaExists = true;
        }
    });

    if(!ccpaExists) {
        ccpaInput.val('');
    }else {
        ccpaInput.val('1');
    }

    submitBtn.addClass('enabled');
}

function addBanner(){
    jQuery('#cb-region__add__banner').on('click', function(){
        let regionTableItems = jQuery( '.cb-region__table__item' ).length;
        const data = jQuery( '.cb-region__table__item:last' );
        let counter = (data.attr('data-next-banner')) ? parseInt(data.attr('data-next-banner'))+1 : 0;
        let newBanner = document.createElement('div');
        newBanner.classList.add('cb-region__table__item','cb-region__secondary__banner');
        newBanner.setAttribute('data-next-banner',counter);
        newBanner.innerHTML = data[0].innerHTML;
        let closeCta = jQuery('.cb-region__remove__banner',newBanner);
        if(closeCta.length===0){
            closeCta = document.createElement('div');
            closeCta.classList.add('cb-region__remove__banner','dashicons','dashicons-dismiss');
            newBanner.appendChild(closeCta);
        }
        let group = jQuery('.cb-region__item__group input',newBanner);
        let region = jQuery('.cb-region__item__region input.second-banner-regions',newBanner);
        if( region.length===0 ){
            let regionContainer = jQuery('.cb-region__item__region',newBanner)[0];
            let regionInput = document.createElement('input');
            regionInput.classList.add('second-banner-regions');
            regionInput.setAttribute('type','hidden');
            regionContainer.appendChild(regionInput);
            region = jQuery('.cb-region__item__region input.second-banner-regions',newBanner);
        }
        jQuery('.cb-region__region__selector .default-none',newBanner).removeClass('hidden');
        jQuery('.cb-region__region__selector .selected-regions',newBanner)[0].innerHTML = '';
        jQuery('.selected-region',newBanner).each(function(){this.classList.remove('selected-region')});
        if(regionTableItems<=1){
            jQuery('.cb-region__item__region--primary',newBanner).remove();
            group.prop('disabled', false);
            group.attr('placeholder','1111-1111-1111-1111');
        }
        group.attr('name',`cookiebot-multiple-banners[${counter}][group]`);
        group[0].value='';
        region.attr('name',`cookiebot-multiple-banners[${counter}][region]`).val('');
        jQuery( '.cb-region__table' ).append( newBanner );
    });
}

function removeBanner(){
    const initialValues = jQuery('form').serialize();
    let submitBtn = jQuery('p.submit #submit');
    jQuery(document).on('click','.cb-region__remove__banner', function(){
        const banner = jQuery(this).closest( '.cb-region__table__item' );
        banner.remove();
        let newValues = jQuery('form').serialize();
        if(newValues !== initialValues) {
            submitBtn.addClass('enabled');
        }else{
            submitBtn.removeClass('enabled');
        }
    });
}