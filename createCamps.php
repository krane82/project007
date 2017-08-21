<?php
SELECT cli.id, cri.weekly, cri.postcodes from clients cli left join clients_criteria cri on cli.id=cri.id