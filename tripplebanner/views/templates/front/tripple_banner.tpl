<div id="trippleBanner">
    {if Configuration::get('TRIPPLEBANNER_IMG_1') !== '' && Configuration::get('TRIPPLEBANNER_IMG_2') !== '' && Configuration::get('TRIPPLEBANNER_IMG_3') !== ''}
        <a class="banner-link" href="{Configuration::get('TRIPPLEBANNER_LINK_1')}">
            <img class="banner-img" src="/modules/tripplebanner/views/img/{Configuration::get('TRIPPLEBANNER_IMG_1')}">
        </a>
        <a class="banner-link" href="{Configuration::get('TRIPPLEBANNER_LINK_2')}">
            <img class="banner-img" src="/modules/tripplebanner/views/img/{Configuration::get('TRIPPLEBANNER_IMG_2')}">
        </a>
        <a class="banner-link" href="{Configuration::get('TRIPPLEBANNER_LINK_3')}">
            <img class="banner-img" src="/modules/tripplebanner/views/img/{Configuration::get('TRIPPLEBANNER_IMG_3')}">
        </a>
    {/if}
</div>
