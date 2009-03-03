<?php
/**
 * @name          jmd_count
 * @description   Count stuff.
 * @author        Jon-Michael Deldin
 * @author_uri    http://jmdeldin.com
 * @version       0.1.1
 * @type          0
 * @order         5
 */


/**
 * Counts the number of rows in a table.
 *
 * @param array $atts
 * @param string $atts['table'] MySQL table name.
 * @param string $atts['where'] MySQL WHERE clause.
 */
function jmd_count($atts)
{
    extract(lAtts(array(
        'table' => '',
        'where' => '',
    ), $atts));
    if ($where != '')
        $where = ' where '. $where;
    $sum = getThing("select count(*) from ". safe_pfx($table) ." $where");

    return $sum;
}

/**
 * Evaluate counting results.
 *
 * @param array $atts
 * @property string $atts['eval'] Valid PHP comparison operator.
 * @property string $atts['table'] MySQL table name.
 * @property string $atts['where'] MySQL WHERE clause.
 */
function jmd_if_count($atts, $thing)
{
    extract(lAtts(array(
        'eval' => '',
        'table' => '',
        'where' => '',
    ), $atts));
    global $jmd_count_value;
    $jmd_count_value = jmd_count(array('table' => $table, 'where' => $where));
    // TODO: 4.0.6 compat
    $eval = html_entity_decode($eval);
    $condition = eval("return($jmd_count_value $eval);");
    $out = EvalElse($thing, $condition);

    return parse($out);
}

/**
 * Returns the total number of rows as set by @see jmd_if_count()
 *
 * @param array $atts
 */
function jmd_count_value($atts)
{
    return $GLOBALS['jmd_count_value'];
}

