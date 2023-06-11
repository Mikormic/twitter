<?php
session_start();
function validateAge($birthday, $age = 15)
{
	// $birthday can be UNIX_TIMESTAMP or just a string-date.
	if (is_string($birthday)) {
		$birthday = strtotime($birthday);
	}
	// check
	// 31536000 is the number of seconds in a 365 days year.
	if (time() - $birthday < $age * 31536000) {
		return false;
	} else {
		return true;
	}
}

if (isset($_POST['valider'])) {
	//vérifie si tous les champs sont bien  pris en compte:
	//on peut combiner isset() pour valider plusieurs champs à la fois
	if (!isset($_POST['name'], $_POST['email'], $_POST['year'], $_POST['month'], $_POST['day'], $_POST['password'])) {
		echo "Un des champs n'est pas reconnu.";
	} else {
		//on vérifie le contenu de tous les champs, savoir si ils sont correctement remplis avec les types de valeurs qu'on souhaitent qu'ils aient
		if (!preg_match("#^[\p{L}0-9]{1,50}$#iu", $_POST['username'])) {
			echo "Le pseudo est incorrect, doit contenir seulement des lettres minuscules et/ou des chiffres, d'une longueur minimum de 1 caractère et de 15 maximum.";
		} else {
			//on vérifie le mot de passe:
			if (strlen($_POST['password']) < 8) {
				echo "Le mot de passe doit être d'une longueur minimum de 8 caractères";
			} else {
				if (validateAge($_POST['year'] . "-" . $_POST['month'] . "-" . $_POST['day']) == false) {
					echo "lol puceau";
				} else {
					//on vérifie que l'adresse est correcte:
					if (!preg_match("#^[a-z0-9_-]+((\.[a-z0-9_-]+){1,})?@[a-z0-9_-]+((\.[a-z0-9_-]+){1,})?\.[a-z]{2,30}$#i", $_POST['email'])) {
						echo "L'adresse mail est incorrecte.";
					} else {
						if (strlen($_POST['email']) < 7 or strlen($_POST['email']) > 50) {
							echo "Le mail doit être d'une longueur minimum de 7 caractères et de 50 maximum.";
						} else {
							$host = 'www.webacademie-project.tech';
							$dbname = 'twitter_academy_db';
							$username = 'wac209_user';
							$password = 'wac209';
							try {
								$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
								$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							} catch (PDOException $e) {
							}
							$password =  hash('ripemd160', $_POST['password']."vive le projet tweet_academy");
					$name = $_POST['name'];
					$username = $_POST['username'];
					$email = $_POST['email'];
					 
					$sql = "UPDATE users SET password='$password', name='$name', username='$username' WHERE email='$email'"; 
					$query = $conn->prepare($sql);
								// On exécute
								if ($query->execute()) {
									echo "Inscrit avec succès! Vous pouvez vous connecter: <a href='connexion.php'>Cliquez ici</a>.";
									$TraitementFini = true; //pour cacher le formulaire
								} else {
									echo "Une erreur est survenue, merci de réessayer ou contactez-nous si le problème persiste.";
								}
						}
					}
				}
			}
		}
	}
}





?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="style.css">
	<script src="https://code.jquery.com/jquery-3.6.3.min.js"
		integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
	<div class="container mt-3">
		<h3>Vertically Centered Modal Example</h3>
		<p>Click on the button to open the modal.</p>

		<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
			Open modal
		</button>
	</div>
	<!-- error: The following untracked working tree files would be overwritten by merge:
			script.js
			Please move or remove them before you merge. -->
	<!-- The Modal -->
	<div class="modal" id="myModal">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<!-- Modal body -->
				<div class="modal-body">
					<form method="post" action="./connexion.php">
						<div id="inscription">
							<div id="inscription1">
								<h3>Etape 1/3</h3>
								<input placeholder="Nom et Prénom" type="text" name="name" id="">
								<input placeholder="email" type="email" name="email" id="">
								<h2>Date de naissance</h2>
								<span class="css-901oao css-16my406 r-poiln3 r-bcqeeo r-qvutc0">Cette information ne
									sera pas affichée publiquement. Confirmez votre âge, même si ce compte est pour une
									entreprise, un animal de compagnie ou autre chose.</span>



								<div id="date">
									<div>
										<span class="css-901oao css-16my406 r-poiln3 r-bcqeeo r-qvutc0">Mois</span>
										<select name="month" id="">
											<option disabled="" value="" class="r-kemksi"></option>
											<option value="01" class="r-kemksi">Janvier</option>
											<option value="02" class="r-kemksi">Février</option>
											<option value="03" class="r-kemksi">Mars</option>
											<option value="04" class="r-kemksi">Avril</option>
											<option value="05" class="r-kemksi">Mai</option>
											<option value="06" class="r-kemksi">Juin</option>
											<option value="07" class="r-kemksi">Juillet</option>
											<option value="08" class="r-kemksi">Août</option>
											<option value="09" class="r-kemksi">Septembre</option>
											<option value="10" class="r-kemksi">Octobre</option>
											<option value="11" class="r-kemksi">Novembre</option>
											<option value="12" class="r-kemksi">Décembre</option>
										</select>
									</div>
									<div>
										<span class="css-901oao css-16my406 r-poiln3 r-bcqeeo r-qvutc0">Jour</span>
										<select name="day" id="">
											<option disabled="" value="" class="r-kemksi"></option>
											<option value="01" class="r-kemksi">1</option>
											<option value="02" class="r-kemksi">2</option>
											<option value="03" class="r-kemksi">3</option>
											<option value="04" class="r-kemksi">4</option>
											<option value="05" class="r-kemksi">5</option>
											<option value="06" class="r-kemksi">6</option>
											<option value="07" class="r-kemksi">7</option>
											<option value="08" class="r-kemksi">8</option>
											<option value="09" class="r-kemksi">9</option>
											<option value="10" class="r-kemksi">10</option>
											<option value="11" class="r-kemksi">11</option>
											<option value="12" class="r-kemksi">12</option>
											<option value="13" class="r-kemksi">13</option>
											<option value="14" class="r-kemksi">14</option>
											<option value="15" class="r-kemksi">15</option>
											<option value="16" class="r-kemksi">16</option>
											<option value="17" class="r-kemksi">17</option>
											<option value="18" class="r-kemksi">18</option>
											<option value="19" class="r-kemksi">19</option>
											<option value="20" class="r-kemksi">20</option>
											<option value="21" class="r-kemksi">21</option>
											<option value="22" class="r-kemksi">22</option>
											<option value="23" class="r-kemksi">23</option>
											<option value="24" class="r-kemksi">24</option>
											<option value="25" class="r-kemksi">25</option>
											<option value="26" class="r-kemksi">26</option>
											<option value="27" class="r-kemksi">27</option>
											<option value="28" class="r-kemksi">28</option>
											<option value="29" class="r-kemksi">29</option>
											<option value="30" class="r-kemksi">30</option>
											<option value="31" class="r-kemksi">31</option>
										</select>
									</div>
									<div>
										<span class="css-901oao css-16my406 r-poiln3 r-bcqeeo r-qvutc0">Année</span>
										<select aria-invalid="false" aria-labelledby="SELECTOR_3_LABEL"
											class="r-30o5oe r-1niwhzg r-17gur6a r-1yadl64 r-1nao33i r-1loqt21 r-37j5jr r-1inkyih r-rjixqe r-crgep1 r-1wzrnnt r-1ny4l3l r-t60dpp r-xd6kpl r-1pn2ns4 r-ttdzmv"
											id="SELECTOR_3" data-testid="" name="year">
											<option disabled="" value="" class="r-kemksi"></option>
											<option value="2023" class="r-kemksi">2023</option>
											<option value="2022" class="r-kemksi">2022</option>
											<option value="2021" class="r-kemksi">2021</option>
											<option value="2020" class="r-kemksi">2020</option>
											<option value="2019" class="r-kemksi">2019</option>
											<option value="2018" class="r-kemksi">2018</option>
											<option value="2017" class="r-kemksi">2017</option>
											<option value="2016" class="r-kemksi">2016</option>
											<option value="2015" class="r-kemksi">2015</option>
											<option value="2014" class="r-kemksi">2014</option>
											<option value="2013" class="r-kemksi">2013</option>
											<option value="2012" class="r-kemksi">2012</option>
											<option value="2011" class="r-kemksi">2011</option>
											<option value="2010" class="r-kemksi">2010</option>
											<option value="2009" class="r-kemksi">2009</option>
											<option value="2008" class="r-kemksi">2008</option>
											<option value="2007" class="r-kemksi">2007</option>
											<option value="2006" class="r-kemksi">2006</option>
											<option value="2005" class="r-kemksi">2005</option>
											<option value="2004" class="r-kemksi">2004</option>
											<option value="2003" class="r-kemksi">2003</option>
											<option value="2002" class="r-kemksi">2002</option>
											<option value="2001" class="r-kemksi">2001</option>
											<option value="2000" class="r-kemksi">2000</option>
											<option value="1999" class="r-kemksi">1999</option>
											<option value="1998" class="r-kemksi">1998</option>
											<option value="1997" class="r-kemksi">1997</option>
											<option value="1996" class="r-kemksi">1996</option>
											<option value="1995" class="r-kemksi">1995</option>
											<option value="1994" class="r-kemksi">1994</option>
											<option value="1993" class="r-kemksi">1993</option>
											<option value="1992" class="r-kemksi">1992</option>
											<option value="1991" class="r-kemksi">1991</option>
											<option value="1990" class="r-kemksi">1990</option>
											<option value="1989" class="r-kemksi">1989</option>
											<option value="1988" class="r-kemksi">1988</option>
											<option value="1987" class="r-kemksi">1987</option>
											<option value="1986" class="r-kemksi">1986</option>
											<option value="1985" class="r-kemksi">1985</option>
											<option value="1984" class="r-kemksi">1984</option>
											<option value="1983" class="r-kemksi">1983</option>
											<option value="1982" class="r-kemksi">1982</option>
											<option value="1981" class="r-kemksi">1981</option>
											<option value="1980" class="r-kemksi">1980</option>
											<option value="1979" class="r-kemksi">1979</option>
											<option value="1978" class="r-kemksi">1978</option>
											<option value="1977" class="r-kemksi">1977</option>
											<option value="1976" class="r-kemksi">1976</option>
											<option value="1975" class="r-kemksi">1975</option>
											<option value="1974" class="r-kemksi">1974</option>
											<option value="1973" class="r-kemksi">1973</option>
											<option value="1972" class="r-kemksi">1972</option>
											<option value="1971" class="r-kemksi">1971</option>
											<option value="1970" class="r-kemksi">1970</option>
											<option value="1969" class="r-kemksi">1969</option>
											<option value="1968" class="r-kemksi">1968</option>
											<option value="1967" class="r-kemksi">1967</option>
											<option value="1966" class="r-kemksi">1966</option>
											<option value="1965" class="r-kemksi">1965</option>
											<option value="1964" class="r-kemksi">1964</option>
											<option value="1963" class="r-kemksi">1963</option>
											<option value="1962" class="r-kemksi">1962</option>
											<option value="1961" class="r-kemksi">1961</option>
											<option value="1960" class="r-kemksi">1960</option>
											<option value="1959" class="r-kemksi">1959</option>
											<option value="1958" class="r-kemksi">1958</option>
											<option value="1957" class="r-kemksi">1957</option>
											<option value="1956" class="r-kemksi">1956</option>
											<option value="1955" class="r-kemksi">1955</option>
											<option value="1954" class="r-kemksi">1954</option>
											<option value="1953" class="r-kemksi">1953</option>
											<option value="1952" class="r-kemksi">1952</option>
											<option value="1951" class="r-kemksi">1951</option>
											<option value="1950" class="r-kemksi">1950</option>
											<option value="1949" class="r-kemksi">1949</option>
											<option value="1948" class="r-kemksi">1948</option>
											<option value="1947" class="r-kemksi">1947</option>
											<option value="1946" class="r-kemksi">1946</option>
											<option value="1945" class="r-kemksi">1945</option>
											<option value="1944" class="r-kemksi">1944</option>
											<option value="1943" class="r-kemksi">1943</option>
											<option value="1942" class="r-kemksi">1942</option>
											<option value="1941" class="r-kemksi">1941</option>
											<option value="1940" class="r-kemksi">1940</option>
											<option value="1939" class="r-kemksi">1939</option>
											<option value="1938" class="r-kemksi">1938</option>
											<option value="1937" class="r-kemksi">1937</option>
											<option value="1936" class="r-kemksi">1936</option>
											<option value="1935" class="r-kemksi">1935</option>
											<option value="1934" class="r-kemksi">1934</option>
											<option value="1933" class="r-kemksi">1933</option>
											<option value="1932" class="r-kemksi">1932</option>
											<option value="1931" class="r-kemksi">1931</option>
											<option value="1930" class="r-kemksi">1930</option>
											<option value="1929" class="r-kemksi">1929</option>
											<option value="1928" class="r-kemksi">1928</option>
											<option value="1927" class="r-kemksi">1927</option>
											<option value="1926" class="r-kemksi">1926</option>
											<option value="1925" class="r-kemksi">1925</option>
											<option value="1924" class="r-kemksi">1924</option>
											<option value="1923" class="r-kemksi">1923</option>
											<option value="1922" class="r-kemksi">1922</option>
											<option value="1921" class="r-kemksi">1921</option>
											<option value="1920" class="r-kemksi">1920</option>
											<option value="1919" class="r-kemksi">1919</option>
											<option value="1918" class="r-kemksi">1918</option>
											<option value="1917" class="r-kemksi">1917</option>
											<option value="1916" class="r-kemksi">1916</option>
											<option value="1915" class="r-kemksi">1915</option>
											<option value="1914" class="r-kemksi">1914</option>
											<option value="1913" class="r-kemksi">1913</option>
											<option value="1912" class="r-kemksi">1912</option>
											<option value="1911" class="r-kemksi">1911</option>
											<option value="1910" class="r-kemksi">1910</option>
											<option value="1909" class="r-kemksi">1909</option>
											<option value="1908" class="r-kemksi">1908</option>
											<option value="1907" class="r-kemksi">1907</option>
											<option value="1906" class="r-kemksi">1906</option>
											<option value="1905" class="r-kemksi">1905</option>
											<option value="1904" class="r-kemksi">1904</option>
											<option value="1903" class="r-kemksi">1903</option>
										</select>
									</div>
								</div>
							</div>
							<div id="inscription2">
								<h3>Etape 2/3</h3>
								Envoie de la vérification de l'adresse email
								<a target="_blank" href="./inscription.php">clique pour t'inscrire</a>
							</div>
							<div id="inscription3">
								<h3>Etape 3/3</h3>
								<input type="text" name="username">username
								<input type="password" name="password">mot de passe
							</div>
							<button type="button" id="next1">Suivant</button>
							<button type="button" id="inscrire1">Suivant</button>
							<button type="button" id="next2">S'inscrire</button>
							<button type="button" id="inscrire2">S'inscrire</button>
						</div>
						<input type="submit" class="btn" value="TEST APPUYE ZEBI" name="valider">
					</form>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function () {
			$("#next2").hide();
			$("#inscrire1").hide();
			$("#inscrire2").hide();
			$("#inscription2").hide();
			$("#inscription3").hide();
			$("#inscription4").hide();
			$("#next1").click(function () {
				$("#inscription1").hide();
				$("#next1").hide();
				$("#inscription2").show();
				$("#inscrire1").show();
			});
			$("#inscrire1").click(function () {
				$("#inscription1").hide();
				$("#next1").hide();
				$("#inscription2").hide();
				$("#inscrire1").hide();
				$("#next2").show();
				$("#inscription3").show();
			});
		});
	</script>
</body>

</html>