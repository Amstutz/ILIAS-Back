<?php
include_once("./Services/UIComponent/Panel/classes/class.ilPanelGUI.php");
include_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/classes/Models/Less/class.KitchenSinkLessFile.php");
include_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/classes/Models/class.KitchenSinkSkin.php");

include_once ("Services/Form/classes/class.ilPropertyFormGUI.php");
include_once ("libs/composer/vendor/geshi/geshi/src/geshi.php");


use ILIAS\UI\Implementation\Crawler\Entry as Entry;

/**
 *
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$*
 */
class ilKSDocumentationEntryGUI
{
    /**
     * @var Entry\ComponentEntry
     */
    protected $entry = null;

    /**
     * @var Entry\ComponentEntries
     */
    protected $entries = null;

    /**
     * @var ilCtrl $ctrl
     */
    protected $ctrl;

    /**
     * @var ilKSDocumentationGUI
     */
    protected $parent;

    /**
     * @var ILIAS\UI\Factory
     */
    protected $f = null;

    /**
     * @var ILIAS\UI\Renderer
     */
    protected $r = null;

    /**
     * ilKSDocumentationEntryGUI constructor.
     * @param ilKSDocumentationGUI $parent
     * @param Entry\ComponentEntry $entry
     * @param Entry\ComponentEntries $entries
     */
    public function __construct(ilSystemStyleDocumentationGUI $parent, Entry\ComponentEntry $entry, Entry\ComponentEntries $entries) {
        global $ilCtrl,$DIC;

        $this->f = $DIC->ui()->factory();
        $this->r = $DIC->ui()->renderer();

        $this->setEntry($entry);
        $this->setEntries($entries);
        $this->setParent($parent);
        $this->ctrl = $ilCtrl;

    }

    /**
     * @return string
     */
    public function renderEntry(){
        /**
         * Todo: Needs to go away here.
         * @var ilTemplate $tpl
         */
        global $tpl;

        $tpl->addJavaScript("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/libs/highlight/highlight.pack.js");
        $tpl->addOnLoadCode("hljs.initHighlightingOnLoad();");
        $tpl->addCss("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/libs/highlight/styles/default.css");

        $start =  microtime (true);
        $feature_wiki_links = array();
        foreach($this->entry->getFeatureWikiReferences()as $href){
            $feature_wiki_links[] = $this->f->link($href);
        }

        $description = $this->f->panel()->block("Description",
            $this->f->generic()->container(
                array(
                    $this->f->listing()->descriptive(
                        array(
                            "Purpose" => $this->entry->getDescription()->getProperty("purpose"),
                            "Composition" => $this->entry->getDescription()->getProperty("composition"),
                            "Effect" => $this->entry->getDescription()->getProperty("effect"),
                            "Rivals" => $this->f->listing()->ordered(
                                $this->entry->getDescription()->getProperty("rivals")
                            )
                        )
                    ),
                    $this->f->listing()->descriptive(
                        array(
                            "Background" => $this->entry->getBackground(),
                            "Feature Wiki References" => $this->f->listing()->ordered($feature_wiki_links)
                        )
                    )
                )
            )
        )->withCard(
            $this->f->card(
                "State"
            )->withSections(array(
                $this->f->listing()->descriptive(
                array(
                    "Entry" => $this->entry->getStatusEntry(),
                    "Implementation" => $this->entry->getStatusImplementation()
                ))
            ))
        );

        $rule_listings = array();
        foreach($this->entry->getRulesAsArray() as $categoery => $category_rules){
            $rule_listings[ucfirst($categoery)] = $this->f->listing()->ordered($category_rules);
        }

        $rules = $this->f->panel()->block("Rules",
            $this->f->listing()->descriptive($rule_listings)
        );

        $example_panels = array();

        $start_example = 0;
        $end_example = 0;
        $example_tot_time = 0;

        if($this->entry->getExamples()){
            $nr = 1;
            foreach($this->entry->getExamples() as $name => $path){
                include_once($path);
                $title = "Example ".$nr.": ".ucfirst(str_replace("_"," ",$name));
                $nr++;
                //$start_example =  microtime (true);
                $example = "<div class='well'>".$name()."</div>"; //Executes function loaded in file indicated by 'path'
                //$end_example =  microtime (true);
                $content_part_1 = $this->f->generic()->html($example);
                //$example_tot_time = ($end_example-$start_example);
                //$examples_snippets[] = $this->f->text()->standard("Time to generate and render example: ".$example_tot_time);
                $code = str_replace("<?php\n","",file_get_contents ($path));
                $geshi = new GeSHi($code, "php");
                $content_part_2 = $this->f->generic()->html($geshi->parse_code());
                $content = $this->f->generic()->container(array($content_part_1,$content_part_2));
                $example_panels[] = $this->f->panel()->block($title, $content);
            }
        }

        $examples = $this->f->generic()->container($example_panels);

        $relations = $this->f->panel()->block("Relations",
            $this->f->listing()->descriptive(
                    array(
                        "Parents" => $this->f->listing()->ordered(
                            $this->entries->getParentsOfEntryTitles($this->entry->getId())
                        ),
                        "Descendants" => $this->f->listing()->unordered(
                            $this->entries->getDescendantsOfEntryTitles($this->entry->getId())
                        )
                    )
            )
        );

        $body = $this->f->generic()->container(array($description,$rules,$examples,$relations));

        $bulletin = $this->f->panel()->report($this->entry->getTitle(),$body);

        $mid =  microtime (true);
        $html = $this->r->render($bulletin);
        $end =  microtime (true);

        return /**"Constructing Time: ".($mid-$start)." (without example rendering: ".(($mid-$start)-($end_example-$start_example)).")".
            "</br>Rendering Time: ".($end-$mid)."</br>".
            "</br>Total Time: ".($end-$start)."</br>".**/$html;
    }


    /**
     * @return Entry\ComponentEntry
     */
    public function getEntry()
    {
        return $this->entry;
    }

    /**
     * @param Entry\ComponentEntry $entry
     */
    public function setEntry(Entry\ComponentEntry $entry)
    {
        $this->entry = $entry;
    }

    /**
     * @return Entry\ComponentEntries
     */
    public function getEntries()
    {
        return $this->entries;
    }

    /**
     * @param Entry\ComponentEntries $entries
     */
    public function setEntries(Entry\ComponentEntries $entries)
    {
        $this->entries = $entries;
    }

    /**
     * @return ilKitchenSinkMainGUI
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param ilKitchenSinkMainGUI $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }
}
?>
