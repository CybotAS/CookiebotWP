<?php

use cybot\cookiebot\settings\templates\Header;
use cybot\cookiebot\settings\templates\Main_Tabs;

use cybot\cookiebot\settings\pages\Settings_Page;

/**
 * @var string $cbid
 * @var string $cb_wp
 * @var string $europe_icon
 * @var string $usa_icon
 * @var string $check_icon
 * @var string $link_icon
 */

$header = new Header;
$main_tabs = new Main_Tabs;

$header->display();
?>
<div class="cb-body">
    <div class="cb-wrapper">
       <?php $main_tabs->display('dashboard'); ?>
        <div class="cb-main__content <?= $cbid ? 'sync-account' : '';?>">
            <?php if(!$cbid) :
                $today = new DateTime('now');
                $end_date = new DateTime('2022-12-31');

                if($today<$end_date) :
            ?>
            <div class="cb-main__dashboard__promo">
                <div class="cb-main__dashboard__promo--inner">
                    <div class="cb-dashboard__promo--label"><div class="time-icon"></div><span>End of Year Promotion</span></div>
                    <h2 class="cb-dashboard__promo--title">Get <span class="highlight">30% off</span> your premium Cookiebot CMP subscription*</h2>
                    <a href="https://www.cookiebot.com/en/new-wp-cookie-plugin?utm_source=wordpress&utm_medium=organic&utm_campaign=banner" target="_blank" class="cb-btn cb-promo-btn">SIGN UP NOW</a>
                    <p class="promo-condition">* This promotion is valid until December 31st, 2022 and will be calculated automatically. The discount only applies to new subscriptions and is valid for the first 12 months.</p>
                </div>
            </div>
            <?php
                endif;
                endif;
            ?>
            <div class="cb-main__dashboard__card--container">
                <div class="cb-main__dashboard__card">
                    <div class="cb-main__card__inner <?= $cbid ? 'start_card' : 'account_card'; ?>">
                        <?php if(!$cbid) : ?>
                        <img src="<?= $cb_wp?>" alt="Cookiebot for WordPress" class="cb-wp">
                        <div class="cb-main__card__content">
                            <h2 class="cb-main__card__title"><?= __('I already have a Cookiebot CMP account' , 'cookiebot' ) ; ?></h2>
                            <a href="/wp-admin/admin.php?page=<?= Settings_Page::ADMIN_SLUG?>" class="cb-btn cb-main-btn"><?= __('Connect my existing account' , 'cookiebot' ) ; ?></a>
                        </div>
                        <?php else: ?>
                            <h2 class="cb-main__card__title"><?= __('Your Cookiebot CMP for WordPress solution' , 'cookiebot' ) ; ?></h2>
                            <div class="cb-main__card__success">
                                <div class="cb-btn cb-success-btn"><img src="<?= $check_icon ?>" alt="Check">  <?= __('Congratulations!' , 'cookiebot' ) ; ?></div>
                                <p class="cb-main__success__text"> <?= __('You have added your Domain Group ID to WordPress. You are all set!' , 'cookiebot' ) ; ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="cb-main__dashboard__card">
                    <div class="cb-main__card__inner  <?= $cbid ? 'start_card' : 'new_card'; ?>">
                        <?php if(!$cbid) : ?>
                            <div class="cb-main__card__content">
                                <p class="cb-main__card__label"><?= __('Get started' , 'cookiebot' ) ; ?></p>
                                <h2 class="cb-main__card__title"><?= __('Create a new Cookiebot CMP account' , 'cookiebot' ) ; ?></h2>
                                <a href="https://manage.cookiebot.com/en/signup" target="_blank" class="cb-btn cb-white-btn"><?= __('Create a new account' , 'cookiebot' ) ; ?></a>
                            </div>
                        <?php else: ?>
                            <h3 class="cb-main__card__subtitle"><?= __('Learn more about how to optimize your Cookiebot CMP setup?' , 'cookiebot' ) ; ?></h3>
                            <a href="https://support.cookiebot.com/hc/en-us" target="_blank" class="cb-btn cb-link-btn"><?= __('Visit Help Center' , 'cookiebot' ) ; ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="cb-main__dashboard__card--container">
                <div class="cb-main__dashboard__card">
                    <div class="cb-main__card__inner start_card">
                        <div class="cb-main__video">
                            <iframe src="https://www.youtube.com/embed/eSVFnjoMKFk" title="Cookiebot WordPress Installation" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        <div class="cb-main__card--content">
                            <p class="cb-main__card__label"><?= __('Video guide' , 'cookiebot' ) ; ?></p>
                            <h2 class="cb-main__card__title"><?= __('How to get started with Cookiebot CMP' , 'cookiebot' ) ; ?></h2>
                            <a href="https://support.cookiebot.com/hc/en-us/articles/4408356523282-Getting-started" target="_blank" class="cb-btn cb-link-btn"><?= __('Learn more about Cookiebot CMP' , 'cookiebot' ) ; ?></a>
                        </div>
                    </div>
                </div>
                <div class="cb-main__dashboard__card">
                    <div class="cb-main__card__inner legislations_card">
                        <div class="cb-main__legislation__item">
                            <div class="cb-main__legislation____icon"><img src="<?= $europe_icon ?>" alt="GDPR"></div>
                            <div class="cb-main__legislation__name"><?= __('GDPR' , 'cookiebot' ) ; ?></div>
                            <div class="cb-main__legislation__region"><?= __('Europe' , 'cookiebot' ) ; ?></div>
                            <a href="https://www.cookiebot.com/en/gdpr/" target="_blank" class="cb-btn cb-link-btn external-icon legislation-link"><?= __('Learn More' , 'cookiebot' ) ; ?> <img src="<?= $link_icon ?>" alt="<?= __('Learn More' , 'cookiebot' ) ; ?>"></a>
                        </div>
                        <div class="cb-main__legislation__item">
                            <div class="cb-main__legislation____icon"><img src="<?= $usa_icon ?>" alt="CCPA"></div>
                            <div class="cb-main__legislation__name"><?= __('CCPA' , 'cookiebot' ) ; ?></div>
                            <div class="cb-main__legislation__region"><?= __('North America' , 'cookiebot' ) ; ?></div>
                            <a href="https://www.cookiebot.com/en/what-is-ccpa/" target="_blank" class="cb-btn cb-link-btn external-icon legislation-link"><?= __('Learn More' , 'cookiebot' ) ; ?> <img src="<?= $link_icon ?>" alt="<?= __('Learn More' , 'cookiebot' ) ; ?>"></a>
                        </div>
                        <a href="https://www.cookiebot.com/en/blog/" target="_blank" class="cb-btn cb-link-btn cb-right-btn"><?= __('See other legislations' , 'cookiebot' ) ; ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>