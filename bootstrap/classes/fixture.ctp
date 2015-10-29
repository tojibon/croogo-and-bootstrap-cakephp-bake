<?php
echo "<?php\n";
?>
class <?php echo $model; ?>Fixture extends CakeTestFixture {

<?php if ($table): ?>
	public $table = '<?php echo $table; ?>';

<?php endif; ?>
<?php if ($import): ?>
	public $import = <?php echo $import; ?>;

<?php endif; ?>
<?php if ($schema): ?>
	public $fields = <?php echo $schema; ?>;

<?php endif; ?>
<?php if ($records): ?>
	public $records = <?php echo $records; ?>;

<?php endif; ?>
}
