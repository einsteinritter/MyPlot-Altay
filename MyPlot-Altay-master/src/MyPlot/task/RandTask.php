<?php
declare(strict_types=1);
namespace MyPlot\task;

use MyPlot\MyPlot;
use MyPlot\Plot;
use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\scheduler\Task;

class RandTask extends Task {
	/** @var MyPlot $plugin */
	private $plugin;
	private $plot, $level, $height, $bottomBlock, $plotFillBlock, $plotFloorBlock, $plotBeginPos, $xMax, $zMax, $maxBlocksPerTick, $pos,$test,$plotSize,$test2,$id,$d;

	/**
	 * ClearPlotTask constructor.
	 *
	 * @param MyPlot $plugin
	 * @param Plot $plot
	 * @param int $maxBlocksPerTick
	 */
	public function __construct(MyPlot $plugin, Plot $plot, int $maxBlocksPerTick = 256,$id,$d) {
        $this->id = $id;
		$this->d = $d;
		$this->plugin = $plugin;
		$this->plot = $plot;
		$this->plotBeginPos = $plugin->getPlotPosition($plot);
		$this->level = $this->plotBeginPos->getLevel();
		$plotLevel = $plugin->getLevelSettings($plot->levelName);
		$plotSize = $plotLevel->plotSize;
        $this->plotSize = $plotLevel->plotSize;
		$this->xMax = $this->plotBeginPos->x +2 + $plotSize;
		$this->zMax = $this->plotBeginPos->z +2 + $plotSize;
		$this->height = $plotLevel->groundHeight;
		$this->maxBlocksPerTick = $maxBlocksPerTick;
			$this->pos = new Vector3($this->plotBeginPos->x, $this->height + 1, $this->plotBeginPos->z);
		$this->plugin = $plugin;
		$plugin->getLogger()->info("Clear Task started at plot {$plot->X};{$plot->Z}");
        $this->test  = 0;
        $this->test2  = 0;
	}

	/**
	 * @param int $currentTicks
	 */
	public function onRun(int $currentTick) : void {
        $blocks = 0;
	$block = Block::get($this->id,$this->d);
        while($this->pos->x < $this->xMax) {

			while($this->pos->z < $this->zMax) {

                $this->test2++;
                    if($this->test === 0 || $this->test === $this->plotSize +1){
    					$this->level->setBlock(new Vector3($this->pos->x -1, $this->height + 1, $this->pos->z -1), $block, true);
                    }

                        if($this->test2 === 1 || $this->test2 === $this->plotSize + $this->plotSize){

    					$this->level->setBlock(new Vector3($this->pos->x -1, $this->height + 1, $this->pos->z -1), $block, true);
                        $this->level->setBlock(new Vector3($this->pos->x -1, $this->height + 1, $this->pos->z +$this->plotSize), $block, true);
                        }


					$blocks++;
					if($blocks >= 500) {
						$this->plugin->getScheduler()->scheduleDelayedTask($this, 1);
						return;
					}


				$this->pos->z++;

			}
            $this->test2 = 0;
            $this->test++;
			$this->pos->z = $this->plotBeginPos->z;
			$this->pos->x++;

    }

		$this->plugin->getLogger()->info("new rand at task completed at {$this->plot->X};{$this->plot->Z}");
	}
}
