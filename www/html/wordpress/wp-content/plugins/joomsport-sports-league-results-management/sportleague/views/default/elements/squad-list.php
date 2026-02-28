<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<div class="table-responsive">
	<div class="jsSquadMatchDiv">
		<div class="jsMatchStatTeams visible-xs clearfix">
			<div class="col-xs-6">
				<div class="jstable jsMatchTeam jsMatchStatHome jsactive" data-tab="jsMatchStatHome">
					<div class="jstable-cell jsMatchTeamLogo">
						<?php echo $partic_home ? ($partic_home->getEmblem(false, 0, '', $width)) : ''; ?>
					</div>
					<div class="jstable-cell jsMatchTeamName">
						<div>
							<span>
								<?php echo ($partic_home) ? ($partic_home->getName(false)) : ''; ?>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-6">
				<div class="jstable jsMatchTeam jsMatchStatAway" data-tab="jsMatchStatAway">
					<div class="jstable-cell jsMatchTeamName">
						<div>
							<span>
								<?php echo ($partic_away) ? ($partic_away->getName(false)) : ''; ?>
							</span>
						</div>
					</div>
					<div class="jstable-cell jsMatchTeamLogo">
						<?php echo $partic_away ? ($partic_away->getEmblem(false, 0, 'emblInline', $width)) : ''; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="jsMatchStatHeader jscenter">
			<h3>
				<?php echo __('Starting lineups','joomsport-sports-league-results-management');?>
			</h3>
		</div>
		<div class="jsSquadContent clearfix">
			<div class="col-sm-6 jsMatchStatHome jsactive" data-tab="jsMatchStatHome">
				<div class="jstable">
					<?php for ($intP = 0; $intP < count($rows->lists['squard1']); ++$intP) {
						?>
						<div class="jstable-row">
							<?php
							if (property_exists($rows->lists['squard1'][$intP],'efFirst')) {
								echo '<div class="jstable-cell jsSquadField">';
								echo $rows->lists['squard1'][$intP]->efFirst;
								echo '</div>';
							}
							?>
							<div class="jstable-cell jsSquadPlayerImg">
								<?php echo jsHelperImages::getEmblem($rows->lists['squard1'][$intP]->obj->getDefaultPhoto(), 0, ''); ?>
							</div>
							<div class="jstable-cell jsSquadPlayerName">
								<div>
									<?php echo $rows->lists['squard1'][$intP]->obj->getName(true); ?>
								</div>
								<?php if(property_exists($rows->lists['squard1'][$intP],'efLast')){
									echo '<div class="jsSquadExField">';
									echo $rows->lists['squard1'][$intP]->efLast;
									echo '</div>';
								}
								?>
							</div>
							<div class="jstable-cell jsSquadSubs">
								<?php
								if ($rows->lists['squard1'][$intP]->is_subs == '1') {
									$cimg = $rows->lists['squard1'][$intP]->player_subs?'out-new.png':'out-raw.png';
									echo '<span class="jsSubs"><img src="'.JOOMSPORT_LIVE_URL_IMAGES_DEF.$cimg.'" class="sub-player-ico" title="" alt="" /></span>';

									if ($rows->lists['squard1'][$intP]->minutes) {
										echo '<span class="jsSubsMin">'.$rows->lists['squard1'][$intP]->minutes."'</span>";
									}
								}
								?>
							</div>
						</div>
						<?php
					}

					if (!count($rows->lists['squard1'])) {
						echo '&nbsp;';
					}
					?>
				</div>
			</div>
			<div class="col-sm-6 jsMatchStatAway" data-tab="jsMatchStatAway">
				<div class="jstable">
					<?php for ($intP = 0; $intP < count($rows->lists['squard2']); ++$intP) {
						?>
						<div class="jstable-row">
							<?php
							if(property_exists($rows->lists['squard2'][$intP],'efFirst')){
								echo '<div class="jstable-cell jsSquadField">';
								echo $rows->lists['squard2'][$intP]->efFirst;
								echo '</div>';
							}
							?>
							<div class="jstable-cell jsSquadPlayerImg">
								<?php echo jsHelperImages::getEmblem($rows->lists['squard2'][$intP]->obj->getDefaultPhoto(), 0, ''); ?>
							</div>
							<div class="jstable-cell jsSquadPlayerName">
								<div>
									<?php echo $rows->lists['squard2'][$intP]->obj->getName(true); ?>
								</div>
								<?php
								if(property_exists($rows->lists['squard2'][$intP],'efLast')){
									echo '<div class="jsSquadExField">';
									echo $rows->lists['squard2'][$intP]->efLast;
									echo '</div>';
								}
								?>
							</div>
							<div class="jstable-cell jsSquadSubs">
								<?php
								if ($rows->lists['squard2'][$intP]->is_subs == '1') {
									$cimg = $rows->lists['squard2'][$intP]->player_subs?'out-new.png':'out-raw.png';
									echo '<span class="jsSubs"><img src="'.JOOMSPORT_LIVE_URL_IMAGES_DEF.$cimg.'" class="sub-player-ico" title="" alt="" /></span>';

									if ($rows->lists['squard2'][$intP]->minutes) {
										echo '<span class="jsSubsMin">'.$rows->lists['squard2'][$intP]->minutes."'</span>";
									}
								}
								?>
							</div>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
	<?php if (count($rows->lists['squard1_res']) || count($rows->lists['squard2_res'])) {
		?>
		<div class="jsSquadMatchDiv">
			<div class="jsMatchStatHeader jscenter">
				<h3>
					<?php echo __('Substitutes','joomsport-sports-league-results-management'); ?>
				</h3>
			</div>
			<div class="jsSquadContent clearfix">
				<div class="col-sm-6 jsMatchStatHome jsactive" data-tab="jsMatchStatHome">
					<div class="jstable">
						<?php for ($intP = 0; $intP < count($rows->lists['squard1_res']); ++$intP) {
							?>
							<div class="jstable-row">
								<?php
								if(property_exists($rows->lists['squard1_res'][$intP],'efFirst')){
									echo '<div class="jstable-cell jsSquadField">';
									echo $rows->lists['squard1_res'][$intP]->efFirst;
									echo '</div>';
								}
								?>
								<div class="jstable-cell jsSquadPlayerImg">
									<?php echo jsHelperImages::getEmblem($rows->lists['squard1_res'][$intP]->obj->getDefaultPhoto(), 0, ''); ?>
								</div>
								<div class="jstable-cell jsSquadPlayerName">
									<div>
										<?php echo $rows->lists['squard1_res'][$intP]->obj->getName(true); ?>
									</div>
									<?php
									if(property_exists($rows->lists['squard1_res'][$intP],'efLast')){
										echo '<div class="jsSquadExField">';
										echo $rows->lists['squard1_res'][$intP]->efLast;
										echo '</div>';
									}
									?>
								</div>
								<div class="jstable-cell jsSquadSubs">
									<?php
									if ($rows->lists['squard1_res'][$intP]->is_subs == -1) {
										echo '<span class="jsSubs"><img src="'.JOOMSPORT_LIVE_URL_IMAGES_DEF.'in-new.png" class="sub-player-ico" title="" alt="" /></span>';

										if ($rows->lists['squard1_res'][$intP]->minutes) {
											echo '<span class="jsSubsMin">'.$rows->lists['squard1_res'][$intP]->minutes."'</span>";
										}

										$subsA = explode(',',$rows->lists['squard1_res'][$intP]->player_subsarray);

										if(isset($subsA[1])){
											$minA = explode(',',$rows->lists['squard1_res'][$intP]->minarray);
											$cimg = $subsA[1]?'out-new.png':'out-raw.png';
											echo '<span class="jsSubs"><img src="'.JOOMSPORT_LIVE_URL_IMAGES_DEF.$cimg.'" class="sub-player-ico" title="" alt="" /></span>';
											echo '<span class="jsSubsMin">'.$minA[1]."'</span>";
										}
									}
									?>
								</div>
							</div>
							<?php
						}
						if (!count($rows->lists['squard1_res'])) {
							echo '&nbsp;';
						}
						?>
					</div>
				</div>
				<div class="col-sm-6 jsMatchStatAway" data-tab="jsMatchStatAway">
					<div class="jstable">
						<?php for ($intP = 0; $intP < count($rows->lists['squard2_res']); ++$intP) {
							?>
							<div class="jstable-row">
								<?php
								if(property_exists($rows->lists['squard2_res'][$intP],'efFirst')){
									echo '<div class="jstable-cell jsSquadField">';
									echo $rows->lists['squard2_res'][$intP]->efFirst;
									echo '</div>';
								}
								?>
								<div class="jstable-cell jsSquadPlayerImg">
									<?php echo jsHelperImages::getEmblem($rows->lists['squard2_res'][$intP]->obj->getDefaultPhoto(), 0, ''); ?>
								</div>
								<div class="jstable-cell jsSquadPlayerName">
									<div>
										<?php echo $rows->lists['squard2_res'][$intP]->obj->getName(true); ?>
									</div>
									<?php
									if(property_exists($rows->lists['squard2_res'][$intP],'efLast')){
										echo '<div class="jsSquadExField">';
										echo $rows->lists['squard2_res'][$intP]->efLast;
										echo '</div>';
									}
									?>
								</div> 
								<div class="jstable-cell jsSquadSubs">
									<?php
									if ($rows->lists['squard2_res'][$intP]->is_subs == -1) {
										echo '<span class="jsSubs"><img src="'.JOOMSPORT_LIVE_URL_IMAGES_DEF.'in-new.png" class="sub-player-ico" title="" alt="" /></span>';

										if ($rows->lists['squard2_res'][$intP]->minutes) {
											echo '<span class="jsSubsMin">'.$rows->lists['squard2_res'][$intP]->minutes."'</span>";
										}

										$subsA = explode(',',$rows->lists['squard2_res'][$intP]->player_subsarray);

										if(isset($subsA[1])){
											$minA = explode(',',$rows->lists['squard2_res'][$intP]->minarray);
											$cimg = $subsA[1]?'out-new.png':'out-raw.png';
											echo '<span class="jsSubs"><img src="'.JOOMSPORT_LIVE_URL_IMAGES_DEF.$cimg.'" class="sub-player-ico" title="" alt="" /></span>';
											echo '<span class="jsSubsMin">'.$minA[1]."'</span>";
										}
									}
									?>
								</div>
							</div>
							<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	?>
</div>
