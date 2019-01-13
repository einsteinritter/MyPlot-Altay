<?php
declare(strict_types=1);
namespace MyPlot\subcommand;

use MyPlot\MyPlot;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginDescription;
use pocketmine\plugin\PluginLoader;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;

class RandSubCommand extends PluginCommand
{

    protected $pl;
	/**
	 * @param CommandSender $sender
	 *
	 * @return bool
	 */

	public function __construct(MyPlot $pl)
    {
        $this->pl = $pl;
        parent::__construct("rand", $pl);
        $this->setDescription("Plot Rand");
        $this->setPermission("myplot.command.rand");
        $this->setAliases(["rnd"]);

    }
	/**
	 * @param Player $sender
	 * @param string[] $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, $label, array $args) : bool {
		$plot = $this->getPlugin()->getPlotByPosition($sender);
		if($plot === null) {
			$sender->sendMessage(TextFormat::RED . $this->translateString("notinplot"));
			return true;
		}
		if($plot->owner !== $sender->getName() and !$sender->hasPermission("myplot.admin.rand")) {
			$sender->sendMessage(TextFormat::RED . $this->translateString("notowner"));
			return true;
		}

	    $fdata = [];

	    $fdata['title'] = '§eCityBuild§8│§7 /p rand';
	    $fdata['buttons'] = [];
	    $fdata['content'] = "";
	    $fdata['type'] = 'form';

	    $fdata['buttons'][] = ['text' => '§cBack'];
        $fdata['buttons'][] = ['text' => '§bDiamond'];
        $fdata['buttons'][] = ['text' => '§eBeacon'];
        $fdata['buttons'][] = ['text' => '§5Gold'];
        $fdata['buttons'][] = ['text' => '§eLuft'];
        $fdata['buttons'][] = ['text' => '§4Reset'];



	    $pk = new ModalFormRequestPacket();
	    $pk->formId = 35335;
	    $pk->formData = json_encode($fdata);

	    $sender->sendDataPacket($pk);

		return true;
	}
}
