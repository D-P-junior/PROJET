<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MyShop</title>
  <link rel="stylesheet" href="../css/index.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>

  <header>
   
        <a href="index.html" class="logo">myshop</a>

        

        <nav>
          <a href="#">Accueil</a>
          <a href="#boutique">Boutique</a>
          <a href="#new">Nouveautés</a>
          <a href="#">Soldes</a>
          <a href="#footer">À propos</a>
        </nav>

        <div class="header-icons">

        <div class="search-wrapper">
            <input type="text" placeholder="Rechercher..." />
            <i class="fa fa-search"></i>
        </div>

        <div class="account">
            <i class="fa fa-user"></i>
            <div class="dropdown">
              <?php if ($_SESSION['pseudo']): ?>
              <a href="deco.php">Deconnexion</a>
              <a href="#">Page Admin</a>
              <?php else:?>
              <a href="signin.php">Connexion</a>
              <a href="signup.php">Inscription</a>
              <?php endif; ?>
            
            </div>
        </div>

        <i class="fa fa-shopping-bag"></i>
        </div> 

        <div class="hamburger">&#9776;</div>
        

        <nav class="nav">
          <a href="#">Accueil</a>
          <a href="#boutique">Boutique</a>
          <a href="#new">Nouveautés</a>
          <a href="#">Soldes</a>
          <a href="#footer">À propos</a>
        </nav>
    
  </header>

  <section class="hero">
    <div class="hero-text">
      <h1>Collection Été 2025</h1>
      <p>Découvrez nos dernières nouveautés conçues pour le confort et le style. Une qualité supérieure qui dure.</p>
      <div class="hero-buttons">
        <a href="#">Achetez maintenant</a>
        <a href="#">Explorez la collection</a>
      </div>
    </div>
    <div class="hero-image">
      <img src="../image/prin-removebg-preview.png" alt="Summer Collection 2025" />
    </div>
  </section>

  <section class="categories">
  <h2>Acheter par Categorie</h2>
  <div class="categories-grid">

    <div class="category-card">
      <img src="../image/cat1.jpg" alt="Women" />
      <div class="category-info">
        <span>Women</span>
        <a href="#">Voir la Collection</a>
      </div>
    </div>

    <div class="category-card">
      <img src="../image/cat2.jpg" alt="Men" />
      <div class="category-info">
        <span>Men</span>
        <a href="#">Voir la Collection</a>
      </div>
    </div>

    <div class="category-card">
      <img src="../image/cat3.jpg" alt="Accessories" />
      <div class="category-info">
        <span>Accessories</span>
        <a href="#">Voir la Collection</a>
      </div>
    </div>

    <div class="category-card">
      <img src="../image/cat4.jpg" alt="Footwear" />
      <div class="category-info">
        <span>Footwear</span>
        <a href="#">Voir la Collection</a>
      </div>
    </div>

  </div>
</section>

<section class="products" id="boutique">
  <div class="products-header">
    <h2>Produits Vedettes</h2>
    <div class="products-filter">
      <a href="#" class="filter-btn active">Tous</a>
      <a href="#" class="filter-btn">Nouveautés</a>
      <a href="#" class="filter-btn">Meilleures Ventes</a>
    </div>
  </div>

  <div class="products-grid">

    <div class="product-card">
      <div class="product-image">
        <span class="badge badge-new">Nouveau</span>
        <img src="../image/pro1.jpg" alt="Chemise Blanche Élégante" />
        <div class="product-actions">
          <a href="#" class="action-btn">&#9825;</a>
          <a href="#" class="action-btn action-btn-blue">&#128722;</a>
        </div>
      </div>
      <div class="product-info">
        <h3>Chemise Blanche Élégante</h3>
        <p class="description">Une chemise classique et élégante pour toutes les occasions.</p>
        <p class="price">49,99$</p>
      </div>
    </div>

    <div class="product-card">
      <div class="product-image">
        <span class="badge badge-best">Meilleure Vente</span>
        <img src="../image/pro2.jpg" alt="Jean Premium" />
        <div class="product-actions">
          <a href="#" class="action-btn">&#9825;</a>
          <a href="#" class="action-btn action-btn-blue">&#128722;</a>
        </div>
      </div>
      <div class="product-info">
        <h3>Jean Premium</h3>
        <p class="description">Un jean confortable et stylé pour un look décontracté.</p>
        <p class="price">79,99$</p>
      </div>
    </div>

    <div class="product-card">
      <div class="product-image">
        <img src="../image/pro3.jpg" alt="Veste en Cuir Classique" />
        <div class="product-actions">
          <a href="#" class="action-btn">&#9825;</a>
          <a href="#" class="action-btn action-btn-blue">&#128722;</a>
        </div>
      </div>
      <div class="product-info">
        <h3>Veste en Cuir Classique</h3>
        <p class="description">Une veste en cuir intemporelle pour un style affirmé.</p>
        <p class="price">199,99$</p>
      </div>
    </div>

    <div class="product-card">
      <div class="product-image">
        <span class="badge badge-sale">Solde</span>
        <img src="../image/pro4.jpg" alt="Robe Florale d'Été" />
        <div class="product-actions">
          <a href="#" class="action-btn">&#9825;</a>
          <a href="#" class="action-btn action-btn-blue">&#128722;</a>
        </div>
      </div>
      <div class="product-info">
        <h3>Robe Florale d'Été</h3>
        <p class="description">Une robe légère et fleurie parfaite pour l'été.</p>
        <div class="price-wrapper">
          <p class="price">59,99$</p>
          <p class="price-old">79,99$</p>
        </div>
      </div>
    </div>

  </div>

  <div class="products-footer">
    <a href="#" class="btn-all">Voir tous les produits</a>
  </div>

</section>


<section class="categories" id="new">
  <h2>Nouveauté</h2>

  <div class="products-grids">

    <div class="product-card">
      <div class="product-image">
        <span class="badge badge-new">Nouveau</span>
        <img src="../image/new1.jpg" alt="Chemise Blanche Élégante" />
        <div class="product-actions">
          <a href="#" class="action-btn">&#9825;</a>
          <a href="#" class="action-btn action-btn-blue">&#128722;</a>
        </div>
      </div>
      <div class="product-info">
        <h3>Chemise Blanche Élégante</h3>
        <p class="description">Une chemise classique et élégante pour toutes les occasions.</p>
        <p class="price">49,99$</p>
      </div>
    </div>

    <div class="product-card">
      <div class="product-image">
        <span class="badge badge-new">Nouveau</span>
        <img src="../image/new2.jpg" alt="Jean Premium" />
        <div class="product-actions">
          <a href="#" class="action-btn">&#9825;</a>
          <a href="#" class="action-btn action-btn-blue">&#128722;</a>
        </div>
      </div>
      <div class="product-info">
        <h3>Jean Premium</h3>
        <p class="description">Un jean confortable et stylé pour un look décontracté.</p>
        <p class="price">79,99$</p>
      </div>
    </div>

    <div class="product-card">
      <div class="product-image">
        <span class="badge badge-new">Nouveau</span>
        <img src="../image/new3.jpg" alt="Veste en Cuir Classique" />
        <div class="product-actions">
          <a href="#" class="action-btn">&#9825;</a>
          <a href="#" class="action-btn action-btn-blue">&#128722;</a>
        </div>
      </div>
      <div class="product-info">
        <h3>Veste en Cuir Classique</h3>
        <p class="description">Une veste en cuir intemporelle pour un style affirmé.</p>
        <p class="price">199,99$</p>
      </div>
    </div>

  </div>

  <div class="products-footer">
    <a href="#" class="btn-all">Voir tous les produits</a>
  </div>
</section>


<section class="newsletter" id="footer">
  <div class="newsletter-inner">
    <h2>Restez Connecté</h2>
    <p>Recevez nos dernières offres et nouveautés directement dans votre boîte mail.</p>
    <form class="newsletter-form" action="#" method="POST">
      <input type="email" name="email" placeholder="Votre adresse email..." />
      <button type="submit">S'abonner</button>
    </form>
  </div>
</section>

<footer>
  <div class="footer-top">

    <div class="footer-brand">
      <a href="index.html" class="logo">myshop</a>
      <p>Votre destination mode pour des vêtements de qualité premium à prix accessible.</p>
      <div class="footer-socials">
        <a href="#">Facebook</a>
        <a href="#">Instagram</a>
        <a href="#">Twitter</a>
      </div>
    </div>

    <div class="footer-links">
      <h4>Boutique</h4>
      <a href="#">Nouveautés</a>
      <a href="#">Femmes</a>
      <a href="#">Hommes</a>
      <a href="#">Accessoires</a>
      <a href="#">Soldes</a>
    </div>

    <div class="footer-links">
      <h4>Aide</h4>
      <a href="#">FAQ</a>
      <a href="#">Livraison</a>
      <a href="#">Retours</a>
      <a href="#">Tailles</a>
      <a href="#">Contact</a>
    </div>

    <div class="footer-links">
      <h4>À Propos</h4>
      <a href="#">Notre Histoire</a>
      <a href="#">Carrières</a>
      <a href="#">Presse</a>
      <a href="#">Partenaires</a>
    </div>

  </div>

  <div class="footer-bottom">
    <p>© 2025 MyShop. Dieu ne joue pas aux dés.</p>
    <div class="footer-legal">
      <a href="#">Confidentialité</a>
      <a href="#">Conditions</a>
      <a href="#">Cookies</a>
    </div>
  </div>
</footer>

</body>
</html>