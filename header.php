<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php if ( function_exists( 'astra_head_top' ) ) astra_head_top(); ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php if ( function_exists( 'wp_body_open' ) ) wp_body_open(); ?>
<?php if ( function_exists( 'astra_body_top' ) ) astra_body_top(); ?>

<div id="page" class="site">
  <?php if ( function_exists( 'astra_header_before' ) ) astra_header_before(); ?>

  <?php get_template_part( 'template-parts/header/header', 'main' ); ?>

  <?php if ( function_exists( 'astra_header_after' ) ) astra_header_after(); ?>
  <?php if ( function_exists( 'astra_content_before' ) ) astra_content_before(); ?>

  <div id="content" class="site-content">
    <?php if ( function_exists( 'astra_content_top' ) ) astra_content_top(); ?>
