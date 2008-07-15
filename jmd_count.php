<?php
$plugin = array(
    'description' => 'Count stuff.',
    'type' => 0,
    'version' => '0.1.1',
);
if (!defined('txpinterface')) include_once '../zem_tpl.php';

if (0) {
?>

# --- BEGIN PLUGIN HELP ---

h1. jmd_count: Count stuff.

This plugin counts rows in "Textpattern tables(Table reference)":http://textpattern.net/wiki/index.php?title=Database_Schema_Reference and plugin-created tables.

h2. @<txp:jmd_count table="table_name" where="where_clause"/>@

|_. Attribute |_. Available values |_. Default value |
| @table@ | Existing MySQL table | -- | 
| @where@ | Valid @WHERE@ clause | -- |

h3. Example: Return the number of pending articles:

bc. <txp:jmd_count table="textpattern" where="Status=3"/>

h2. @<txp:jmd_if_count eval="comparison" table="table_name" where="where_clause">@

Same attributes as @jmd_count@ with the addition of @eval@.

|_. Attribute |_. Available values |_. Default value |_. Description |
| @eval@ | "Comparison operators":http://php.net/operators.comparison | -- | Compare the counted rows to @eval@ (e.g., @eval=">=1"@) |

Note: For 4.0.6 you _must_ use HTML entities i.e., @eval="&gt;=1"@

bc. <txp:jmd_if_count eval=">30" table="txp_form">
    Whoah! <txp:jmd_count_value/> forms is ridonkulous.
</txp:jmd_if_count>

h2. @<txp:jmd_count_value/>@

No attributes. Returns the value from @jmd_if_count@.

h2. Example: Site statistics

bc.. <table>
    <tr>
        <th>Item</th>
        <th>Count</th>
    </tr>
    <tr>
        <td>Live articles</td>
        <td>
            <txp:jmd_count table="textpattern" where="Status = 4"/>
        </td>
    </tr>
    <tr>
        <td>Comments</td>
        <td>
            <txp:jmd_count table="txp_discuss" where="visible = 1"/>
        </td>
    </tr>
    <tr>
        <td>Images</td>
        <td>
            <txp:jmd_count table="txp_image"/>
        </td>
    </tr>
    <tr>
        <td>Files</td>
        <td>
            <txp:jmd_count table="txp_file"/>
        </td>
    </tr>
    <tr>
        <td>Site hits</td>
        <td>
            <txp:jmd_count table="txp_log"/>
        </td>
    </tr>
</table>

p. Tip: Combine jmd_count with "jmd_dashboard":http://jmdeldin.com/?plugins. :)

# --- END PLUGIN HELP ---

<?php
}

# --- BEGIN PLUGIN CODE ---

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
    {
        $where = ' where '. $where;
    }
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

# --- END PLUGIN CODE ---
?>
