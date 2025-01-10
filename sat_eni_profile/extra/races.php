<?php
//if(!defined('SAT_ENI_PROFILE_INSTALL')) die();

$races = array(
	array(
		'id'	=> 1,
		'name'	=> 'Люди',
		'desc'	=> 'Люди – самая многочисленная раса Энирина. На заре времён Единый создал не один народ людей, а сразу несколько, поместив их в разных уголках старого мира. В настоящее время люди расселились почти по всему континенту, образовав множество новых народов со своими внешними и культурными особенностями.'
	),
	array(
		'id'	=> 2,
		'name'	=> 'Эльфы',
		'desc'	=> 'Эльфы — одна из основных рас Энирина, отличающаяся долгим сроком жизни. Они обладают рядом особенностей, таких как сосредоточенность на одном-двух ремёслах, успешность в магии и щепетильное отношение к смерти. Эльфы сталкиваются с трудностями в продолжении рода из-за относительно короткого - около двухсот лет после совершеннолетия - репродуктивного периода и низкой вероятности зачатия. Между разными народами эльфов существуют напряжённые отношения, основанные на давних обидах. Однако у них есть и общие черты, такие как любовь к прекрасному и предпочтение живого обучения. Внешне эльфы отличаются худощавым телосложением, тонкими чертами лица, большими глазами и заострёнными ушами.'
	),
	array(
		'id'	=> 3,
		'name'	=> 'Гномы',
		'desc'	=> ''
	),
	array(
		'id'	=> 4,
		'name'	=> 'Гоблиноиды',
		'desc'	=> ''
	),
	array(
		'id'	=> 5,
		'name'	=> 'Вампиры',
		'desc'	=> ''
	),
	array(
		'id'	=> 7,
		'name'	=> 'Полукровки',
		'desc'	=> ''
	),
	array(
		'id'	=> 8,
		'name'	=> 'Бестиалы',
		'desc'	=> ''
	),
	array(
		'id'	=> 9,
		'name'	=> 'Мески',
		'basis'	=> 1,
		'desc'	=> 'Мески светлокожи. Мужчины обычно коротко стригут волосы и бороды, но среди знати широко распространены длинные шевелюры. Мески – народ твёрдый, трудолюбивый и весьма усердный в своих занятиях, будь то земледелие, ремесло, путешествия или война. Они склонны к коллективизму, религиозны и нравственны; практически все поклоняются Единому. В большинстве своём населяют северные земли Альтанара, однако кровь месков оставила следы и в других частях континента.<br />Язык: северный альтик.<br />Имя: как правило, одно личное имя и фамилия (у знати перед фамилией есть приставка "фон" или "цу"). Ориентир: немецкие имена.'
	),
	array(
		'id'	=> 10,
		'name'	=> 'Амморийцы',
		'basis'	=> 1,
		'desc'	=> 'Амморийцы – потомки месканских конкистадоров и покорённых аборигенов Вермы и Дождливого леса; черты первых в большинстве современных амморийцев преобладают. Для них характерна любовь к пёстрым нарядам. Амморийцы деятельны, упрямы и свободолюбивы – эти качества они унаследовали от предков-завоевателей и за прошедшие столетия буквально возвели в культ «хорошего амморийца». Дух путешествий и новых открытий также весьма силён в них. В вопросах веры они серьёзны и консервативны; подавляющее большинство – прихожане Церкви Единого. Густо населяют восток королевства Вермилон, составляя значительную (и крайне патриотичную) часть вермилонской нации.<br />Язык: аммори (разновидность альтика).<br />Имя: одно личное имя и фамилия, у знати включает родословную по мужской линии. Ориентир: испанские и португальские имена.'
	),
	array(
		'id'	=> 11,
		'name'	=> 'Остманны',
		'basis'	=> 1,
		'desc'	=> 'Народ, возводящий своё происхождение к месканским пионерам. Остманны имеют смуглую кожу, многие отличаются ростом выше среднего и сравнительной тонкостью черт. Известны своей ловкостью, предприимчивостью и малой заботой о принципах. Поклонение Единому в их краях претерпело некоторые изменения, в частности остманнское духовенство благосклонно смотрит на работорговлю (за исключением продажи единоверцев). Большинству из них свойственна неприязнь к оркам.<br />Язык: остик (разновидность альтика), смесь северного альтика и аль-шанкры.<br />Имя: одно или несколько (у знати) личных имён и фамилия. Ориентир: нидерландские имена.'
	),
	array(
		'id'	=> 12,
		'name'	=> 'Джерцы',
		'basis'	=> 1,
		'desc'	=> 'Почти полтора тысячелетия жизни под тёмными небесами города вампиров изменили облик джерцев, их кожа стала бледна, а черты лица заострились. Для них характерен рост ниже среднего и светлые цвета волос – среди них много блондинов, русоволосых и даже альбиносов, реже встречаются темноволосые. Нарядам джерцев присуща строгая однотонная вычурность, белый цвет считается траурным. Для них характерен социальный уклад, в котором главную роль играет коммуна, живущая в служении вампиру-аристократу или же тесно связанная с работой на производстве (вроде верфей и мануфактур). Джерцы – преданная паства Темиары, многие из них мечтают об обращении в вампира. Они – обитатели столицы вампирской империи, города Джера, и её ближайших окрестностей в верховьях реки Тридо. Иногда джерцами ошибочно называют всех подданных Джера.<br />Язык: джерский, певучий, с обилием гласных и ударением на последнем слоге.<br />Имя: одно или несколько личных имён и фамилия. Ориентир: французские имена.'
	),
	array(
		'id'	=> 13,
		'name'	=> 'Хёллинги',
		'basis'	=> 1,
		'desc'	=> 'Почти все северяне светловолосы, светлоглазы, с очень бледной кожей. Изредка можно встретить темноволосых и рыжих. Большинство низкорослы, но очень крепко сложены и совсем не склонны к полноте. Мужчины бреют или коротко постригают бороды; длинная заплетённая борода – атрибут вождя или жреца. Причёски отличаются большим разнообразием и изобретательностью у обоих полов; рабам, преступникам и изгоям выбривают череп наголо. Дети снегов жестоки, суровы, грубы, выносливы и бесстрашны. Их отличает звериная хитрость и коварство. Они поклоняются Единому, известному здесь под именем Крадхар, полностью лишенному в этой версии сострадания и милосердия. Дети и женщины считаются собственностью господина, однако женщина может стать воином, если поднимет оружие против обидчика. Колдовство разрешено только для женщинам и жрецам. Хёллинги населяют Хёлленланд, Стылый Берег и Ледяной Порог (большей частью входящих в королевство Харпанварг). Их можно встретить в качестве наёмников на службе одного из южных государств.<br />Язык: хёлл-тала.<br />Имя: личное имя и патроним (с постфиксами -сон/-доттир для мужчин/женщин). Широко распространены прозвища. Ориентир: скандинавские имена.'
	),
	array(
		'id'	=> 14,
		'name'	=> 'Валоны',
		'basis'	=> 1,
		'desc'	=> 'Для валонов характерны средний рост, нормальное сложение и светлая кожа с лёгким загаром. Они предпочитают светлые одежды, богачи часто выходят в свет в пёстрых нарядах. Валоны жизнерадостны, импульсивны, им свойственно быть великодушными и восторженными. Поклоняются Единому, полагаясь в этом больше на эмоции, чем на разум; очень любят пышные церемонии (как религиозные, так и светские). Валонами густо населены центральные и южные провинции Альтанара.<br />Язык: высокий альтик, отличается мягким и певучим звучанием.<br />Имя: одно или несколько (у знати) личных имён и фамилия. Ориентир: итальянские имена.'
	),
	array(
		'id'	=> 15,
		'name'	=> 'Кольны',
		'basis'	=> 1,
		'desc'	=> 'Кольны — смуглокожие, темноволосые и темноглазые жители юго-восточных земель за Сторожевой грядой. Они делятся на два субэтноса: южных и северных кольнов. Южные кольны из герцогства Фрогрум и с юга герцогства Харнрум светлее и немного выше своих северных сородичей, а некоторые даже похожи на жителей юга Альтанара. Их культура долгое время находилась под влиянием Валонии, и они поклоняются Единому. Северные кольны населяют восток княжества Румиват — это самая многочисленная часть народа. Все кольны энергичны, отважны и вспыльчивы, любят громко спорить, но при необходимости могут быть сдержанными и хитрыми. Их национальная религия — коривианство, основанное на поклонении пяти богам. Они ненавидят огров и драконов, своих прежних хозяев.<br />Язык: северный и южный кольник (разновидность альтика), различаются произношением.<br />Имя: одно личное имя и фамилия. Ориентир: румынские, валашские имена.'
	),
	array(
		'id'	=> 16,
		'name'	=> 'Хаанны',
		'basis'	=> 1,
		'desc'	=> 'Хаанны — это народ с небольшим ростом, крепким телосложением и оливковой кожей, покрытой загаром. У них тёмные волосы и карие или чёрные миндалевидные глаза. Большинство хааннов ведут оседлый образ жизни, но пятая часть народа продолжает кочевать. Их общество основано на кланах, как у кочевников, так и у оседлых хааннов. Кочевые хаанны не склонны к полноте, в отличие от своих оседлых сородичей. Они гордятся своим традиционным укладом и не любят чужаков, но в своём кругу могут быть безмятежными и весёлыми. Оседлые хаанны — трудолюбивые и стойкие люди. Они известны своим чувством юмора и бережливостью, чётко разграничивая «своё» и «чужое». Оседлые хаанны поклоняются Единому, в то время как кочевники придерживаются старых хаанских культов. Хаанны населяют королевство Вермилон и юг Ингиренвальда.<br />Язык: хаамнори, древний и певучий язык кочевников.<br />Имя: одно личное имя и фамилия. Ориентир: тюркские, алтайские имена.'
	),
	array(
		'id'	=> 17,
		'name'	=> 'Аскарди',
		'basis'	=> 1,
		'desc'	=> 'Аскарди — один из самых высоких народов континента, многие представители которого обладают крепким телосложением благодаря постоянному физическому труду. Почти все имеют светлую кожу, не тронутую загаром. Блондины, брюнеты и шатены встречаются в равной степени, а глаза чаще всего бывают голубыми или карими. Аскарди известны спокойствием, рассудительностью и практичностью. Они не терпят суеты и не переносят лень. Гостеприимство играет важную роль в их культуре. Многие мужчины обучаются владению оружием.<br />Аскарди спокойно относятся к сверхъестественным существам, их жизненный уклад полон суеверий и мистических явлений. Волшебники не вызывают у них враждебности, но обычно они относятся к ним настороженно, большим почётом пользуются алхимия и артефакторика.<br />Большинство представителей этого народа поклоняются Единому, хотя их вера испытала некоторое влияние традиционных суеверий. Аскарди населяют земли на крайнем западе континента, включая королевство Вермилон, Аскардскую лигу, Таннаратскую республику и великое герцогство Айсгардское. Большая диаспора аскарди проживает в Альтанаре.<br />Язык: гальфар, имеет несколько региональных разновидностей, но обычно аскарди из разных уголков Запада друг друга понимают.<br />Имя: одно или несколько личных имён и фамилия. Ориентир: кельтские имена.'
	),
	array(
		'id'	=> 18,
		'name'	=> 'Гвендалики',
		'basis'	=> 1,
		'desc'	=> 'Большинство гвендаликов имеют невысокий рост, светлую кожу и глаза, чаще всего голубые. На севере преобладают брюнеты, а на юге — блондины. Отличительная черта этого народа — любовь к татуировкам, связанным с религией, происхождением, социальным статусом, положением в обществе, профессией и даже количеством детей.<br />Гвендалики — фаталисты с мрачным характером. В прошлом они пострадали от проклятия, превратившего север Нейтральных лесов в Земли Скверны. Они враждебно относятся к чёрной магии, но уважают то, что они называют белой магией. Гвендские колдуны часто выступают в роли жрецов и духовных советников при маркграфах.<br />Большинство гвендаликов являются последователями Темиары, некоторые поклоняются Единому, но их вера содержит местные суеверия. Они населяют север Ингиренвальда, а также являются частью населения Таннаратской республики и великого герцогства Айсгардского.<br />Язык: гвендалик.<br />Имя: одно личное имя и фамилия. Ориентир: финские и прибалтийские имена.'
	),
	array(
		'id'	=> 19,
		'name'	=> 'Верманны',
		'basis'	=> 1,
		'desc'	=> 'Верманны — рослые, светловолосые люди со светлой кожей. Большинство имеет серые или голубые глаза. Некоторые верманны из южных областей посмуглели, а из северных стали украшать свои тела татуировками. Верманны с западных территорий приобрели эльфийское изящество черт. Этот народ произошёл от месканских первопроходцев и унаследовал их предприимчивость, гордость и воинственность. Жизнь рядом с духами и эльфами повлияла на их культуру и привела к появлению культа Владычицы Озера, которая почитается как Святая Единого, которому они поклоняются.<br />Язык: верманнский, произошедший от северного альтика при влиянии джерского и эденвайтского эльфийского языков.<br />Имя: одно личное имя и фамилия. Ориентир: чешские имена.'
	),
	array(
		'id'	=> 20,
		'name'	=> 'Лирцы',
		'basis'	=> 1,
		'desc'	=> 'Лирцы — белокуры или рыжеволосы, светлоглазы, со светлой кожей. Обычно они среднего роста, мужчины крепкие и ширококостные, а женщины обладают аппетитными формами. В мирное время лирцы трудолюбивы и дружелюбны, хотя и немного суеверны. Однако их знаменитое гостеприимство было подорвано в результате кровопролитных войн, которыми печально известна долина Лир в последнее время. В результате этого лирцы стали более недоверчивыми, бережливыми и сопротивляются любым попыткам отобрать их имущество. Лирцы населяют обширные территории в долине Лир. Западные лирцы, которые постепенно перенимают веру и образ жизни хозяев-джерцев, более покладистые и стремятся к миру, в то время как восточные лирцы, покорившиеся Джеру, стали озлобленными и беспринципными.<br />Язык: лирский, самобытное местное наречие, испытавшее влияние джерского и сельмирионского эльфийского языков.<br />Имя: одно личное имя и фамилия. Ориентир: Ориентир: польские имена.'
	),
	array(
		'id'	=> 21,
		'name'	=> 'Лирские цыгане',
		'basis'	=> 1,
		'desc'	=> 'Цыгане — смуглые люди с тёмными волосами, тёмными глазами миндалевидной формы и ростом не выше среднего. Они предпочитают носить яркую и свободную одежду, любят украшения и часто сочетают красный и чёрный цвета. Цыгане весёлые, пылкие и страстные, известны как музыканты, артисты, мошенники и воры. Большинство из них кочует и получает средства к существованию благодаря случайным заработкам, актёрскому ремеслу, мелкой торговле и воровству. Воинственность им не свойственна, но горячность и язвительность иногда приводят к дракам. Они называют себя лирийцами и встречаются по всему континенту. Многие из них поклоняются Единому, но есть и те, кто принимает другие верования.<br />Язык: цыганский – взрывная смесь множества людских и нелюдских языков.<br />Имя: одно личное имя, иногда с прозвищем, возможно заимствование патронимов и фамилий.'
	),
	array(
		'id'	=> 22,
		'name'	=> 'Западные горцы',
		'basis'	=> 1,
		'desc'	=> 'Горцы в основном невысокого роста, широкоплечие, с тёмными волосами и глазами. У них грубая, смуглая кожа, привыкшая к холоду и жаре. Мужчины носят пояса, а женщины — ленты, окрашенные в цвета своего клана. Знатные горцы носят цветные костюмы. Западные горцы считаются дикими и необузданными. Они прямолинейны, честны и мужественны, привыкли стойко переносить трудности и защищать близких. Однако нельзя не отметить их грубость, вспыльчивость и подозрительное отношение к незнакомцам. Особенно сильную неприязнь горцы испытывают к гномам. Кроме того, испытывая нужду практически во всём, горцы часто совершают набеги на своих более успешных и благополучных соседей, чтобы добыть припасы.<br />Западные горцы живут в горах Кхазад-Дара, в основном в северной части массива. Республика Гурубаши, дружественная вестгорцам, распространяет среди них цивилизацию и веру в Единого, но в целом они продолжают придерживаться своих традиций и поклоняться местным божествам и тотемным животным.<br />Язык: казаррин – самое распространённое из наречий горцев Кхазад-Дара. Не имеет письменности. Некоторые племена говорят на диалектах-изолятах.<br />Имя: личное имя и прозвище, у вождей - патроним. Ориентир: венгерские имена.'
	),
	array(
		'id'	=> 23,
		'name'	=> 'Восточные горцы',
		'basis'	=> 1,
		'desc'	=> 'Горцы в основном невысокие, крепкие и коренастые, с бледной или розоватой кожей, которая часто бывает грубой и обветренной. У них длинные причёски, и мужчины отращивают бороды, которые искусно заплетают. Горцы — суровые, хладнокровные и требовательные люди, которые действуют размеренно и неторопливо. Феодализм и патриархально-клановая система смешиваются в их обществе, создавая своеобразный социум, где сеньор является вождём клана и старшим родственником, которому нужно беспрекословно подчиняться. Большинство горцев являются последователями Единого, хотя некоторые кланы сохраняют старые анималистические верования и ведут уединённый образ жизни, часто кочуя. Горцы населяют альтанарскую часть Сторожевой гряды и встречаются на спорных территориях в её средней части.<br />Язык: каменный альтик, отличается резким и гортанным звучанием.<br />Имя: одно или несколько (у знати) личных имён и фамилия. Феодалы имеют приставку «фон» перед фамилией. Ориентир: немецкие имена.'
	),
		array(
		'id'	=> 24,
		'name'	=> 'Шанки',
		'basis'	=> 1,
		'desc'	=> 'Шанки имеют смуглую кожу, от оливкового до шоколадного оттенков, тёмные волосы и глаза. Многие из них худощавы, но не хрупки. Их одежда удобна и долговечна. Будучи плодом смешения множества рас и народов, народ пустыни безусловно считает себя людьми, но при этом терпимо относятся к представителям других рас и конфессий. Они практичны, коварны, алчны и безжалостны, заботятся только о себе и своей семье. Однако купцы шанки известны своей верностью слову. Шанки поклоняются Единому, но их вера отличается от сигмальдианства и коривианства. Некоторые из них следуют культам Младших богов.
Шанки населяют пустыню Хаст и прилегающие территории, пустыню Асмаэль, а также встречаются в горах и на севере Альтанара.<br />Язык: аль-шанкра (пустынное наречие): официальная (высокопарный стиль, используемый преимущественно в письменности), высокая (разговорный стиль, разнящийся для Хаста и Асмаэли) и низкая (местный диалект конкретного города).<br />Имя: В зависимости от статуса к личному имени может добавляться: патроним, фамилия (у знати), титулы, прозвища. Ориентир: арабские и персидские имена.'
	),
	array(
		'id'	=> 25,
		'name'	=> 'Тильяри',
		'basis'	=> 2,
		'desc'	=> ''
	),
	array(
		'id'	=> 26,
		'name'	=> 'Синдореи',
		'basis'	=> 2,
		'desc'	=> ''
	),
	array(
		'id'	=> 27,
		'name'	=> 'Тель`квессир',
		'basis'	=> 2,
		'desc'	=> ''
	),
	array(
		'id'	=> 28,
		'name'	=> 'Ди`эль Шайя',
		'basis'	=> 2,
		'desc'	=> ''
	)
);


// — Демоны — Демоны желаний иномирцы

// — Вентури — Вел'кари — Оборотни — Ведьмаки — Драконы


if ($forum_db->table_exists('races')) {
	$schema = array(
		'DELETE'	=> 'races'
	);
	$forum_db->query_build($schema);
}

foreach ($races as $val) {
	$str = strval($val['id']).", '".$val['name']."', ".(!empty($val['basis']) ? strval($val['basis']).', ' : "")."'".$val['desc']."'";
	$schema = array(
		'INSERT'	=> 'id, name, '.(!empty($val['basis']) ? 'basis, ' : '').'description',
		'INTO'		=> 'races',
		'VALUES'	=> $str		
	);
	$forum_db->query_build($schema);
}

$unavailable = array();
foreach ($unavailable as $item) {
	$schema = array(
		'UPDATE'	=> 'races',
		'SET'		=> 'available = 0',
		'WHERE'		=> 'id = '.strval($item)
	);
	$forum_db->query_build($schema);
}