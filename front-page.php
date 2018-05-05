<?php
/**
 * The front page template file
 *
 * @package WordPress
 * @subpackage Plotter
 * @since 1.0
 * @version 1.0
 */

get_header();

if ( isset( $_REQUEST['error'] ) && (int) $_REQUEST['error'] == 404 ) {
  include locate_template( '404.php' );
} else {
?>

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="jumbotron">
        <div class="container">
          <h1 class="text-center"><i class="plt-quill3"></i> Plotter</h1>
          <p class="text-center">&#x2014; Let's weave new story! &#x2014;</p>
          
          <div class="text-center">
            <ul class="list-inline">
<?php if ( ! is_user_logged_in() ) : ?>
              <li><a href="<?= wp_login_url(); ?>" title="<?php _e( 'Sign In', WPGENT_DOMAIN ); ?>" class="btn btn-default"><?php _e( 'Sign In', WPGENT_DOMAIN ); ?></a></li>
              <li><a href="<?= wp_registration_url(); ?>" title="<?php _e( 'Create Account', WPGENT_DOMAIN ); ?>" class="btn btn-default"><?php _e( 'Create Account', WPGENT_DOMAIN ); ?></a></li>
<?php else : ?>
              <li><a href="/dashboard/" title="<?= __( 'To Dashboard', WPGENT_DOMAIN ) ?>" class="btn btn-default"><?= __( 'To Dashboad', WPGENT_DOMAIN ) ?></a></li>
              <li><a href="<?= wp_logout_url( home_url( '/' ) ); ?>" title="<?= __( 'Sign Out', WPGENT_DOMAIN ) ?>" class="btn btn-default"><?= __( 'Sign Out', WPGENT_DOMAIN ) ?></a></li>
<?php endif; ?>
            </ul>
          </div>
          
        </div><!-- /.container -->
      </div><!-- /.jumbotron -->
      
      <div>
        <p class="lead">Lorem ipsum dolor sit amet, duo at meliore corrumpit reformidans, dicat dicta ne cum. Altera molestie at pro, no mutat scripta duo, denique maluisset et has. Id quaeque appareat constituam sea, hendrerit dissentiunt ad has. Ex ridens maiestatis voluptaria vix, quando iudicabit eam te. Ne nonumes expetenda mei, est alia tota congue te.</p>
        <p class="h4">No mei officiis assentior dissentiunt, te saepe labores forensibus est. Integre omnesque epicurei ne eam, ea elitr option tacimates eum, ne usu affert adipiscing. Vel ea probo legendos, te deserunt moderatius sed, idque vulputate cu eos. Nec ne stet solum, nam movet labitur probatus an, ceteros fuisset denique cum cu. Mea eirmod tincidunt ad.</p>
        <p class="h5">Cu cum modus temporibus efficiantur, in percipit comprehensam sea. An cum numquam contentiones, an vis elit abhorreant. At tollit ponderum assueverit vis. Vis graeci honestatis at, decore option detraxit id qui, aperiri adipisci inciderint an sit. Ut choro viderer quaeque usu, vel te modus porro epicuri. Ne aperiam persequeris nam, te populo facilis euripidis eum.</p>
        <p class="h6">Cu sit legere euismod necessitatibus, no eius tritani ocurreret qui. Docendi antiopam eam an, nostrud graecis cu has. Nulla oratio et mea. Eu vim dicat eirmod vivendum.</p>
        <p>Ut oporteat argumentum sea. Nam ut volumus postulant rationibus, eu tale esse feugiat pro. Te vidisse bonorum mei. In summo lobortis deseruisse est. Eu posse epicurei vis, eam eu case erroribus evertitur, ius luptatum accusata torquatos ut. Id usu vitae vocent copiosae, usu ad fugit ludus recteque. Mel quot commune id, est an ponderum evertitur.</p>
<!-- /*
Cum an libris altera voluptaria, dignissim theophrastus at has. Possim apeirian recusabo eu pri, ex alia delicata consectetuer est. Ubique tacimates reprimique eum ut, ius nulla dicam no. Nulla eirmod ad pri, eu odio pertinacia cum, erat omnes elaboraret et vix. Eos id oratio eleifend, modo erat ei est, ut est graecis vulputate. In congue maluisset posidonium est, nisl nostrum incorrupte his te.

Cu eirmod appareat sea, nullam legere partiendo pro ex. Cum magna nobis fierent id. Verterem democritum eu mea. Iudico discere ea sit. Usu no utinam fabellas definiebas.

In vide quaestio cum, natum eripuit hendrerit at eum, ad sit scripta appareat. Vix ad fugit omnesque cotidieque. Per vidisse vocibus legendos at. Est id solum soleat qualisque. Veritus vocibus volumus no per. Ex tamquam impedit vim.

Ut sed consequat persecuti, id duo sale idque suscipiantur, ne his nobis efficiantur. Ex mel wisi facete fierent, ad sit error postea mediocrem. Id audire convenire constituam cum, in ius etiam tempor, usu no ipsum sensibus. Laudem quaestio ne nec. At quo dico nonumy placerat.

Semper comprehensam mel ex. Mei aliquip integre accumsan ei. Vel constituam consectetuer ut, et pro adhuc graeci periculis. Ius ut electram adolescens reprehendunt, eu has solet epicurei, sea id elit partem tempor. Vidisse aliquando an duo, voluptaria honestatis et vel. Pro case noster ea, ex eos laboramus forensibus, deleniti evertitur no nec.
*/ -->
        <ul>
          <li><a href="/service/">Service</a></li>
          <li><a href="/service/user-policies/">User Policies</a></li>
          <li><a href="/service/privacy-policy/">Privacy Policy</a></li>
          <li><a href="/service/cookie-policy/">Cookie Policy</a></li>
        </ul>
      </div>
      
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->

<?php 
}

get_footer();
