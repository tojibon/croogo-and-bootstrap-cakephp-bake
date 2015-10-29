<?php
echo "<?php\n";
?>
<?php foreach ($uses as $dependency): ?>
App::uses('<?php echo $dependency[0]; ?>', '<?php echo $dependency[1]; ?>');
<?php endforeach; ?>

<?php if ($type === 'Controller'): ?>
class <?php echo $fullClassName; ?>Test extends ControllerTestCase {
<?php else: ?>
class <?php echo $fullClassName; ?>Test extends CakeTestCase {
<?php endif; ?>

<?php if (!empty($fixtures)): ?>
	public $fixtures = array(
		'<?php echo join("',\n\t\t'", $fixtures); ?>'
	);

<?php endif; ?>
<?php if (!empty($construction)): ?>
	public function setUp() {
		parent::setUp();
<?php echo $preConstruct ? "\t\t" . $preConstruct : ''; ?>
		$this-><?php echo $className . ' = ' . $construction; ?>
<?php echo $postConstruct ? "\t\t" . $postConstruct : ''; ?>
	}

	public function tearDown() {
		unset($this-><?php echo $className; ?>);

		parent::tearDown();
	}

<?php endif; ?>
<?php foreach ($methods as $method): ?>
	public function test<?php echo Inflector::camelize($method); ?>() {
		$this->markTestIncomplete('test<?php echo Inflector::camelize($method); ?> not implemented.');
	}

<?php endforeach; ?>
}
