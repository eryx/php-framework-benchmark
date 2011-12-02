<?php

/* WebProfilerBundle:Profiler:layout.html.twig */
class __TwigTemplate_43ff0d931d348bcadbd3ac0ecd9a7407 extends Twig_Template
{
    protected $parent;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = array();
        $this->blocks = array(
            'panel' => array($this, 'block_panel'),
            'body' => array($this, 'block_body'),
        );
    }

    public function getParent(array $context)
    {
        $parent = "WebProfilerBundle:Profiler:base.html.twig";
        if ($parent instanceof Twig_Template) {
            $name = $parent->getTemplateName();
            $this->parent[$name] = $parent;
            $parent = $name;
        } elseif (!isset($this->parent[$parent])) {
            $this->parent[$parent] = $this->env->loadTemplate($parent);
        }

        return $this->parent[$parent];
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $context = array_merge($this->env->getGlobals(), $context);

        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 26
    public function block_panel($context, array $blocks = array())
    {
        echo "";
    }

    // line 3
    public function block_body($context, array $blocks = array())
    {
        // line 4
        echo "
    ";
        // line 5
        echo $this->env->getExtension('actions')->renderAction("WebProfilerBundle:Profiler:toolbar", array("token" => $this->getContext($context, 'token'), "position" => "normal"), array());
        // line 6
        echo "
    <div id=\"content\">
        ";
        // line 8
        $this->env->loadTemplate("WebProfilerBundle:Profiler:header.html.twig")->display(array());
        // line 9
        echo "
        ";
        // line 10
        if ($this->getContext($context, 'profile')) {
            // line 11
            echo "            <div id=\"resume\">
                <p>
                    <strong><a href=\"";
            // line 13
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'profile'), "url", array(), "any", false), "html");
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'profile'), "url", array(), "any", false), "html");
            echo "</a></strong>
                    <span class=\"date\">
                        <strong>by ";
            // line 15
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'profile'), "ip", array(), "any", false), "html");
            echo "</strong> at <strong>";
            echo twig_escape_filter($this->env, twig_date_format_filter($this->getAttribute($this->getContext($context, 'profile'), "time", array(), "any", false), "r"), "html");
            echo "</strong>
                    </span>
                </p>
            </div>
        ";
        }
        // line 20
        echo "
        <div id=\"main\">

            <div class=\"clear_fix\">
                <div id=\"collector_wrapper\">
                    <div id=\"collector_content\">
                        ";
        // line 26
        $this->displayBlock('panel', $context, $blocks);
        // line 27
        echo "                    </div>
                </div>
                <div id=\"navigation\">
                    ";
        // line 30
        if (array_key_exists("templates", $context)) {
            // line 31
            echo "                        <ul id=\"menu_profiler\">
                            ";
            // line 32
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getContext($context, 'templates'));
            foreach ($context['_seq'] as $context['name'] => $context['template']) {
                // line 33
                echo "                                ";
                ob_start();
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'template'), "renderBlock", array("menu", array("collector" => $this->getAttribute($this->getContext($context, 'profile'), "getcollector", array($this->getContext($context, 'name'), ), "method", false)), ), "method", false), "html");
                $context['menu'] = new Twig_Markup(ob_get_clean());
                // line 34
                echo "                                ";
                if (($this->getContext($context, 'menu') != "")) {
                    // line 35
                    echo "                                    <li class=\"";
                    echo twig_escape_filter($this->env, $this->getContext($context, 'name'), "html");
                    if (($this->getContext($context, 'name') == $this->getContext($context, 'panel'))) {
                        echo " selected";
                    }
                    echo "\">
                                        <a href=\"";
                    // line 36
                    echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("_profiler", array("token" => $this->getContext($context, 'token'), "panel" => $this->getContext($context, 'name'))), "html");
                    echo "\">";
                    echo $this->getContext($context, 'menu');
                    echo "</a>
                                    </li>
                                ";
                }
                // line 39
                echo "                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['name'], $context['template'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 40
            echo "                        </ul>
                    ";
        }
        // line 42
        echo "                    ";
        echo $this->env->getExtension('actions')->renderAction("WebProfilerBundle:Profiler:searchBar", array(), array());
        // line 43
        echo "                    ";
        $this->env->loadTemplate("WebProfilerBundle:Profiler:admin.html.twig")->display(array("token" => $this->getContext($context, 'token')));
        // line 44
        echo "                </div>
            </div>
        </div>
    </div>
";
    }

    public function getTemplateName()
    {
        return "WebProfilerBundle:Profiler:layout.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }
}
