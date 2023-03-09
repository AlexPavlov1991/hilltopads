try {
  // Label::addLabels(Label::ENTITY_TYPE_USER, 3, []);
  // Label::deleteLabels(Label::ENTITY_TYPE_USER, 1, []);
  // Label::updateLabels(Label::ENTITY_TYPE_USER, 1, ['red', 'green', 'blue']);
  $labels = Label::getLabels(Label::ENTITY_TYPE_USER, 2);

  var_dump($labels);
} catch (\Exception $e) {
    echo "Ошибка: " . $e->getMessage();
}
