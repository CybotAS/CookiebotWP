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
    const region_list = jQuery('.cb-region__region__list');
    jQuery('.cb-region__region__selector').on('click', function(){
        region_list.removeClass('hidden');
    });

    jQuery('.cb-region__veil').on('click', function(){
        region_list.addClass('hidden');
        let newValues = jQuery('form').serialize();
        if(newValues !== initialValues) {
            submitBtn.addClass('enabled');
        }else{
            submitBtn.removeClass('enabled');
        }
    });
}

function selectRegion() {
    jQuery('.cb-region__region__item').on('click', function(){
        let code = jQuery(this).data('region');
        let name = jQuery(this).text();
        if(jQuery(this).hasClass('selected-region')){
            jQuery(this).removeClass('selected-region');
            toggleCode(code,name);
        }else{
            jQuery(this).addClass('selected-region');
            toggleCode(code,name);
        }
    });
}

function toggleCode(code,name) {
    const regionInput = jQuery('#second-banner-regions');
    const regionVal = jQuery('#second-banner-regions').val();
    const ccpaInput = jQuery('#ccpa-compatibility');
    const submitBtn = jQuery('p.submit #submit');
    const selectedBox = jQuery('.selected-regions');
    let regionList = regionVal.split(', ');

    if(regionList[0]==='')
        regionList = [];

    if(regionList.indexOf(code)!==-1){
        regionList.splice(regionList.indexOf(code),1);
        if(code==='US-06')
            ccpaInput.val('');
    }else{
        regionList.push(code);
        if(code==='US-06')
            ccpaInput.val('1');
    }

    let itemSelector = '#'+code;
    let selected = jQuery(itemSelector);

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
        jQuery('.default-none').removeClass('hidden');
    }else{
        jQuery('.default-none').addClass('hidden');
    }
    regionInput.val(newRegions);
    submitBtn.addClass('enabled');
}