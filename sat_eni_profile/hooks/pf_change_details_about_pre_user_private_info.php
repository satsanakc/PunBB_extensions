<?php if (!empty($forum_page['society'])): ?>
			<div class="char ct-set data-set set<?php echo ++$forum_page['item_count'] ?>">
				<div class="ct-box data-box">
					<h3 class="ct-legend hn"><span><?php echo $lang_eni_profile['fields'][1] ?></span></h3>
					<ul class="data-list">
						<?php echo implode("\n\t\t\t\t\t\t", $forum_page['society'])."\n" ?>
					</ul>
				</div>
			</div>
<?php endif;

if (!empty($forum_page['skills'])): ?>
			<div class="char ct-set data-set set<?php echo ++$forum_page['item_count'] ?>">
				<div class="ct-box data-box">
					<h3 class="ct-legend hn"><span><?php echo $lang_eni_profile['skills'] ?></span></h3>
					<div class="sig-demo"><?php echo $forum_page['skills'] ?></div>
				</div>
			</div>
<?php endif;

if (!empty($forum_page['person'])): ?>
			<div class="char ct-set data-set set<?php echo ++$forum_page['item_count'] ?>">
				<div class="ct-box data-box">
					<h3 class="ct-legend hn"><span><?php echo $lang_eni_profile['person'] ?></span></h3>
					<div class="sig-demo"><?php echo $forum_page['person'] ?></div>
				</div>
			</div>
<?php endif;

if (!empty($forum_page['bio'])): ?>
			<div class="char ct-set data-set set<?php echo ++$forum_page['item_count'] ?>">
				<div class="ct-box data-box">
					<h3 class="ct-legend hn"><span><?php echo $lang_eni_profile['bio'] ?></span></h3>
					<div class="sig-demo"><?php echo $forum_page['bio'] ?></div>
				</div>
			</div>
<?php endif;

if (!empty($forum_page['extra'])): ?>
			<div class="char ct-set data-set set<?php echo ++$forum_page['item_count'] ?>">
				<div class="ct-box data-box">
					<h3 class="ct-legend hn"><span><?php echo $lang_eni_profile['extra'] ?></span></h3>
					<div class="sig-demo"><?php echo $forum_page['extra'] ?></div>
				</div>
			</div>
<?php endif;