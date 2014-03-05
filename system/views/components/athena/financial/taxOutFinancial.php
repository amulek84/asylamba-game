<?php
# taxOutFinancial component
# in athena package

# détail l'imposition sectorielle

# require
	# [{orbitalBase}]			ob_taxOutFinancial

$totalTaxOut = 0;

# bonus
$taxBonus = CTR::$data->get('playerBonus')->get(PlayerBonus::POPULATION_TAX);

# view part
echo '<div class="component financial">';
	echo '<div class="head skin-1">';
		echo '<img src="' . MEDIA . 'financial/taxout.png" alt="" />';
		echo '<h2>Redevances</h2>';
		echo '<em>Redevances aux factions</em>';
	echo '</div>';
	echo '<div class="fix-body">';
		echo '<div class="body">';
			echo '<ul class="list-type-1">';
				foreach ($ob_taxOutFinancial as $base) {
					$baseTaxOut = (Game::getTaxFromPopulation($base->getPlanetPopulation()) + (Game::getTaxFromPopulation($base->getPlanetPopulation()) * $taxBonus / 100)) * $base->getTax() / 100;
					$totalTaxOut += $baseTaxOut;

					echo '<li>';
						echo '<span class="label">' . $base->getName() . ' [' . $base->getTax() . '% de taxe]</span>';
						echo '<span class="value">';
							echo Format::numberFormat($baseTaxOut);
							echo '<img class="icon-color" src="' . MEDIA . 'resources/credit.png" alt="crédits" />';
						echo '</span>';
					echo '</li>';
				}

				echo '<li class="strong">';
					echo '<span class="label">total de la redevance</span>';
					echo '<span class="value">';
						echo Format::numberFormat($totalTaxOut);
						echo '<img class="icon-color" src="' . MEDIA . 'resources/credit.png" alt="crédits" />';
					echo '</span>';
				echo '</li>';
			echo '</ul>';

			echo '<p class="info">La redevance de faction est une taxe que vous devez payer. Cette taxe est versée à la faction qui a le contrôle 
			du secteur dans lequel vous vous situez. De ce fait, vous pouvez très bien verser un impôt à une faction ennemie. Cette taxe est 
			versée chaque relève.</p>';
		echo '</div>';
	echo '</div>';
echo '</div>';