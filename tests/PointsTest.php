<?php

namespace ML_Express\Graphics\Tests;

require_once 'src/Points.php';
require_once 'src/Point.php';
require_once 'src/Angle.php';

use ML_Express\Graphics\Points;
use ML_Express\Graphics\Point;
use ML_Express\Graphics\Angle;

class PointsTest extends \PHPUnit_Framework_TestCase
{
	public function testAddPoints()
	{
		$expected = [];
		$points1 = Points::create();
		foreach ([[2, 2], [3, 2], [3, 3], [2, 3]] as $p) {
			$points1->addPoint(Point::create($p[0], $p[1]));
			$expected[] = $p;
		}
		$points2 = Points::create();
		foreach ([[0, 0], [1, 0], [1, 1], [0, 1]] as $p) {
			$points2->addPoint(Point::create($p[0], $p[1]));
			$expected[] = $p;
		}
		$this->assertEqualPointArrays($expected, $points1->addPoints($points2));
	}

	public function rectangleProvider()
	{
		return array(
				array(
						[[10, 20], [110, 20], [110, 100], [10, 100]],
						Points::create()->rectangle(Point::create(10, 20), 100, 80)
				),
				array(
						[[10, 20], [10, 100], [110, 100], [110, 20]],
						Points::create()->ccw()->rectangle(Point::create(10, 20), 100, 80)
				)
		);
	}

	/**
	 * @dataProvider rectangleProvider
	 */
	public function testRectangle($expected, $points)
	{
		$this->assertEqualPointArrays($expected, $points);
	}

	public function polygonProvider()
	{
		return array(
				array(
						[[10, -80], [110, 20], [10, 120], [-90, 20]],
						Points::create()->polygon(Point::create(10, 20), 4, 100)
				),
				array(
						[[10, -80], [-90, 20], [10, 120], [110, 20]],
						Points::create()->ccw()->polygon(Point::create(10, 20), 4, 100)
				)
		);
	}

	/**
	 * @dataProvider polygonProvider
	 */
	public function testPolygon($expected, $points)
	{
		$this->assertEqualPointArrays($expected, $points);
	}

	public function starProvider()
	{
		return array(
				array(
						[[10, -80], [60, 20], [10, 120], [-40, 20]],
						Points::create()->star(Point::create(10, 20), 2, 100, 50)
				),
				array(
						[[10, -80], [-40, 20], [10, 120], [60, 20]],
						Points::create()->ccw()->star(Point::create(10, 20), 2, 100, 50)
				),
				array(
						[[10, -80], [60, 20], [10, 120], [-40, 20]],
						Points::create()->star(Point::create(10, 20), 1, 100, [50, 100, 50])
				),
				array(
						[[10, -80], [-40, 20], [10, 120], [60, 20]],
						Points::create()->ccw()->star(Point::create(10, 20), 1, 100, [50, 100, 50])
				)
		);
	}

	/**
	 * @dataProvider starProvider
	 */
	public function testStar($expected, $points)
	{
		$this->assertEqualPointArrays($expected, $points);
	}

	public function sectorProvider()
	{
		$a1 = Angle::byDegrees(45);
		$a2 = Angle::byDegrees(135);
		$l = sin(\deg2rad(45)) * 100;
		return array(
				array(
						[[10, 20], [10 + $l, 20 + $l], [10 - $l, 20 + $l]],
						Points::create()->sector(Point::create(10, 20), $a1, $a2, 100)
				),
				array(
						[[10, 20], [10 - $l, 20 + $l], [10 + $l, 20 + $l]],
						Points::create()->ccw()->sector(Point::create(10, 20), $a1, $a2, 100)
				)
		);
	}

	/**
	 * @dataProvider sectorProvider
	 */
	public function testSector($expected, $points)
	{
		$this->assertEqualPointArrays($expected, $points);
	}

	public function ringSectorProvider()
	{
		$a1 = Angle::byDegrees(45);
		$a2 = Angle::byDegrees(135);
		$l1 = sin(\deg2rad(45)) * 100;
		$l2 = $l1 / 2;
		return array(
				array(
						[[10+$l1, 20+$l1], [10-$l1, 20+$l1], [10-$l2, 20+$l2], [10+$l2, 20+$l2]],
						Points::create()->ringSector(Point::create(10, 20), $a1, $a2, 100, 50)
				),
				array(
						[[10-$l1, 20+$l1], [10+$l1, 20+$l1], [10+$l2, 20+$l2], [10-$l2, 20+$l2]],
						Points::create()->ccw()
								->ringSector(Point::create(10, 20), $a1, $a2, 100, 50)
				)
		);
	}

	/**
	 * @dataProvider ringSectorProvider
	 */
	public function testRingSector($expected, $points)
	{
		$this->assertEqualPointArrays($expected, $points);
	}

	public function assertEqualPointArrays($expected, Points $points)
	{
		$pointsArray = [];
		foreach ($points as $point) {
			$pointsArray[] = [$point->x, $point->y];
		}
		$this->assertEquals($expected, $pointsArray);
	}
}