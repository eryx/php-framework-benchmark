<?php

/* WebProfilerBundle:Collector:events.html.twig */
class __TwigTemplate_ef9378d3e6919cb380d877f5a1273aec extends Twig_Template
{
    protected $parent;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = array();
        $this->blocks = array(
            'menu' => array($this, 'block_menu'),
            'panel' => array($this, 'block_panel'),
        );
    }

    public function getParent(array $context)
    {
        $parent = "WebProfilerBundle:Profiler:layout.html.twig";
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

        // line 3
        $context['__internal_ef9378d3e6919cb380d877f5a1273aec_1'] = $this;
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_menu($context, array $blocks = array())
    {
        // line 6
        echo "<span class=\"label\">
    <span class=\"icon\"><img src=\"";
        // line 7
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/webprofiler/images/profiler/events.png"), "html");
        echo "\" alt=\"Events\" /></span>
    <strong>Events</strong>
</span>
";
    }

    // line 12
    public function block_panel($context, array $blocks = array())
    {
        // line 13
        echo "    <h2>Called Listeners</h2>

    <table>
        <tr>
            <th>Event name</th>
            <th>Listener</th>
        </tr>
        ";
        // line 20
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, 'collector'), "calledlisteners", array(), "any", false));
        foreach ($context['_seq'] as $context['_key'] => $context['listener']) {
            // line 21
            echo "            <tr>
                <td><code>";
            // line 22
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'listener'), "event", array(), "any", false), "html");
            echo "</code></td>
                <td><code>";
            // line 23
            echo twig_escape_filter($this->env, $this->getAttribute($context['__internal_ef9378d3e6919cb380d877f5a1273aec_1'], "display_listener", array($this->getContext($context, 'listener'), ), "method", false), "html");
            echo "</code></td>
            </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['listener'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 26
        echo "    </table>

    ";
        // line 28
        if ($this->getAttribute($this->getContext($context, 'collector'), "notcalledlisteners", array(), "any", false)) {
            // line 29
            echo "        <h2>Not Called Listeners</h2>

        <table>
            <tr>
                <th>Event name</th>
                <th>Listener</th>
            </tr>
            ";
            // line 36
            $context['listeners'] = $this->getAttribute($this->getContext($context, 'collector'), "notcalledlisteners", array(), "any", false);
            // line 37
            echo "            ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable(twig_sort_filter(twig_get_array_keys_filter($this->getContext($context, 'listeners'))));
            foreach ($context['_seq'] as $context['_key'] => $context['listener']) {
                // line 38
                echo "                <tr>
                    <td><code>";
                // line 39
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getContext($context, 'listeners'), $this->getContext($context, 'listener'), array(), "array", false), "event", array(), "any", false), "html");
                echo "</code></td>
                    <td><code>";
                // line 40
                echo twig_escape_filter($this->env, $this->getAttribute($context['__internal_ef9378d3e6919cb380d877f5a1273aec_1'], "display_listener", array($this->getAttribute($this->getContext($context, 'listeners'), $this->getContext($context, 'listener'), array(), "array", false), ), "method", false), "html");
                echo "</code></td>
                </tr>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['listener'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 43
            echo "        </table>
    ";
        }
    }

    // line 47
    public function getdisplay_listener($listener = null)
    {
        $context = array_merge($this->env->getGlobals(), array(
            "listener" => $listener,
        ));

        ob_start();
        try {
            // line 48
            echo "    ";
            if (($this->getAttribute($this->getContext($context, 'listener'), "type", array(), "any", false) == "Closure")) {
                // line 49
                echo "        Closure
    ";
            } elseif (($this->getAttribute($this->getContext($context, 'listener'), "type", array(), "any", false) == "Function")) {
                // line 51
                echo "        ";
                $context['link'] = $this->env->getExtension('code')->getFileLink($this->getAttribute($this->getContext($context, 'listener'), "file", array(), "any", false), $this->getAttribute($this->getContext($context, 'listener'), "line", array(), "any", false));
                // line 52
                echo "        ";
                if ($this->getContext($context, 'link')) {
                    echo "<a href=\"";
                    echo twig_escape_filter($this->env, $this->getContext($context, 'link'), "html");
                    echo "\">";
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'listener'), "function", array(), "any", false), "html");
                    echo "</a>";
                } else {
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'listener'), "function", array(), "any", false), "html");
                }
                // line 53
                echo "    ";
            } elseif (($this->getAttribute($this->getContext($context, 'listener'), "type", array(), "any", false) == "Method")) {
                // line 54
                echo "        ";
                $context['link'] = $this->env->getExtension('code')->getFileLink($this->getAttribute($this->getContext($context, 'listener'), "file", array(), "any", false), $this->getAttribute($this->getContext($context, 'listener'), "line", array(), "any", false));
                // line 55
                echo "        ";
                echo $this->env->getExtension('code')->abbrClass($this->getAttribute($this->getContext($context, 'listener'), "class", array(), "any", false));
                echo "::";
                if ($this->getContext($context, 'link')) {
                    echo "<a href=\"";
                    echo twig_escape_filter($this->env, $this->getContext($context, 'link'), "html");
                    echo "\">";
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'listener'), "method", array(), "any", false), "html");
                    echo "</a>";
                } else {
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'listener'), "method", array(), "any", false), "html");
                }
                // line 56
                echo "    ";
            }
        } catch(Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ob_get_clean();
    }

    public function getTemplateName()
    {
        return "WebProfilerBundle:Collector:events.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }
}
