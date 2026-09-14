<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Hand raise activity module implementation.
 *
 * @package mod_handraise
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$string['emptyqueue'] = 'Ninguém está aguardando.';
$string['handlowered'] = 'Mão abaixada';
$string['handraise:addinstance'] = 'Adicionar uma atividade Levantar a mão';
$string['handraise:managequeue'] = 'Gerenciar a fila para falar';
$string['handraise:raisehand'] = 'Solicitar para falar';
$string['handraised'] = 'Mão levantada';
$string['handraisename'] = 'Nome da atividade';
$string['handserved'] = 'Solicitação atendida';
$string['lowerhand'] = 'Cancelar pedido';
$string['modulename'] = 'Levantar a mão';
$string['modulenameplural'] = 'Atividades Levantar a mão';
$string['pluginadministration'] = 'Administração do Levantar a mão';
$string['pluginname'] = 'Levantar a mão';
$string['positionlabel'] = 'Sua posição: ';
$string['privacy:metadata:handraise_queue'] = 'Armazena a fila atual de pessoas que solicitaram falar em uma atividade Levantar a mão.';
$string['privacy:metadata:handraise_queue:handraiseid'] = 'A atividade Levantar a mão em que a solicitação foi feita.';
$string['privacy:metadata:handraise_queue:timecreated'] = 'A data e hora em que o usuário solicitou falar.';
$string['privacy:metadata:handraise_queue:userid'] = 'O usuário que solicitou falar.';
$string['queue'] = 'Fila para falar';
$string['queuecountlabel'] = 'Pessoas aguardando: ';
$string['raisehand'] = 'Preciso falar';
$string['requestedat'] = 'Solicitado ';
$string['requestfailed'] = 'Não foi possível atualizar a fila. Tente novamente.';
$string['resetqueue'] = 'Limpar filas do Levantar a mão';
$string['serve'] = 'Atendido';
$string['waitingtime'] = 'Aguardando há ';
