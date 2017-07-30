<?php

use www\widgets\common\PaginationWidget;

$problem_presenter = new www\presenters\ProblemPresenter();
?>

<h2 class="ui header">Problems</h2>
<form class="ui form" action="/problems">
    <div class="fields">
        <div class="four wide field">
            <input name="id" placeholder="Problem ID" value="<?= $id ?>">
        </div>
        <div class="ten wide field">
            <input name="title" placeholder="Problem Title" value="<?= $title ?>">
        </div>
        <div class="two wide field">
            <input type="submit" value="Search" class="ui blue submit button"/>
        </div>
    </div>
</form>
<table class="ui selectable celled table">
    <thead>
    <tr>
        <th class="two wide">#</th>
        <th class="nine wide">Title</th>
        <th class="two wide">Total Submissions</th>
        <th class="two wide">AC%</th>
        <th class="one wide">Level</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ((array) $records as $record) {
        echo <<< PROBLEM
    <tr class="{$problem_presenter->showTRClass($record['status'])}">
        <td>{$record['id']} {$problem_presenter->showProblemIcon($record['status'])}</td>
        <td><a href="/problem?id={$record['id']}">{$record['title']}</a></td>
        <td class="center aligned">
            <div class="ui horizontal grey mini statistic">
                <div class="value">{$record['total']}</div>
            </div>
        </td>
        <td>
            {$problem_presenter->showProblemRate($record['accepted'], $record['total'])}
        </td>
        <td>{$record['level']}</td>
    </tr>
PROBLEM;
    }
    ?>
    </tbody>
</table>
<?= PaginationWidget::widget(['pagination' => $pagination]); ?>