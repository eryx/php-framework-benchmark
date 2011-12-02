<?php

/* WebProfilerBundle:Collector:config.html.twig */
class __TwigTemplate_b0fa525679c527ca7c99b7fcee0defa5 extends Twig_Template
{
    protected $parent;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = array();
        $this->blocks = array(
            'toolbar' => array($this, 'block_toolbar'),
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

        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_toolbar($context, array $blocks = array())
    {
        // line 4
        echo "    ";
        ob_start();
        // line 5
        echo "        <a href=\"http://symfony.com/\"><img width=\"26\" height=\"28\" alt=\"Symfony\" style=\"border-width: 0; margin: 0 5px 0 10px; vertical-align: middle;\" src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAcCAYAAAB/E6/TAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAABRZJREFUeNqkVnlM03cUb38tFFqKB4UyGZccYrZyVpnGEQzngAlOQATcFMiWJcZFOZzb4pYNFTYnmYh4/OVEFCSIYAYIbhzCNpm0BMjCBAqFUsohMHrQFmTvbf2RDosWfc3L93rf76e/d1MXFxcp+uTj5bc0F3Q8oiXuS942JpMdVKs1nnK5nK8vy2Qy/2IwGF2WlpZ3KirLb8BdLdyhGCKqISAQpgNA1ODAYK5SqXSnGEEAOGrDtcmtrKq4AEvNi4CoJ7NPb2j4paFgYmIimvISxGazH8XERO8+mnFkGJaLhoCoFwsvOV4vKq4HFblQXoHodLpiq//W6IIL+Q2wXMA9Qh+k+PqN+68KgjQ/P89qe9hWWXKzFN+i6gMx6+vun5mdnd242kcDAt7WItvachdyvj09/k5EuAr3tVot88rlK2WoTRKInpV5LK6vr2/PakHCwsPk/m/5i5uamk1GR2U08L5WXz/fDvJ8cvIJ78O0j9Jhaoo24oSHRjTLZDKP1YC4uLhoyspLb0dF7AqVSCTrcG/N2jV/q+fUZnNzc6aknIWFxZPmlkYebZO7R+C92nufrPRg/N44ZVBwkHy91XqKJZtNlUpH/1X3seNZDebmZm2XLl6OIGUBhAH2oenf12g05qJ+0STR2toauxJI9qlv+oNDgn8990P+uuqfaswlkhGKm5vrvJOzkzIsLPROTXWtOSkL8TPk6uqiNfSOWCyOImamZ3iGDp03Os9FRkYUT01NdZJ7oF76yIiUiI+Pa4HlY4FAuHS3rq5eJpVKCUNvgSpfJ8DTuIYOR6WjqILfQ0NDGiOjIsXkvkKhINzc3RBo0tTUhMHjvSlHzj93nq9QKGmG3hoYGHQgaDQ63dChSqUySTmQmoRy2Se/vsrz5Clxn8PhzPH5fkI0SVdnN6+zs8sC+UXOQ1Cp/wUUSbav2T5FfVtbW6tBNQngnrthuxnU+BDPHRzsp2CYQJcdHx+3NdZLn9GpGYOhsnewr1nU5abhYUkCDFNDQ0P4OGWDnZ0ENXj2TF6gsSBWHCsV4bF58/AyfbJSD6b5czhWShaLuQCR3g9eF3LrVlkUnnt5e6JzLPb29u4wFsjB3n6K7u3t1X236u7/6kx7u8ATRzTy7fKKHeB54eTZli38dhhovb19/sYCbfLwEBF7Yt/7Ge1hSACNDCBLhkZHcHR0FOfmfBewGvsk70+sRRv17k2IFxpzwY/vN4Rpv7Gh8WNjQQJ3Bkrt7OwECDSQmpZSggG6XKig8PxITu4pGbne/35Sw4EPUg5DYBpVdVks1tNPj2eVYnATOlf9LTMroxYS4FIVtLGx0Wzfvq1EJBr4E9e7ot+VXrta5NEh7Ag19muOph/5g8vl1sFUQlZYK8z6134s+uzs93lvkIKQ17oJgkaHn+2YbIwB5d3MWJCMzPTHScmJeZgGgUdIIEwdDsBRoP+0Lz4/wYNKS33ZCgva6UtM2lcI0ypgEdZB/Z7BVAe2EwpWzJcnvgpsedDCXA2Aj4+39tDhQwJfX5+bsKwG7ic7ouVdEOY9a3Qw4CChsGNnaUmpW3PTA/OVvhC6HgrEojo2PlYEJb0JthrR5phU9NuuZ/o6XTOBseMEjIHrjTEHTuA0Oytn9fT0sCHj0/h8/jSbbaHw8vbCzN4H3KFjVNUM2f08D4gkmq6xsMFcq/tSLNlrdTlyGkuFzmvHdIwA80Z1qs8BZWDO1TF+NcYddjzq5f/eEP0jwAAGCybA1KhGOwAAAABJRU5ErkJggg==\"/></a>
    ";
        $context['icon'] = new Twig_Markup(ob_get_clean());
        // line 7
        echo "    ";
        $this->env->loadTemplate("WebProfilerBundle:Profiler:toolbar_item.html.twig")->display(array_merge($context, array("link" => false, "text" => $this->getAttribute($this->getContext($context, 'collector'), "symfonyversion", array(), "any", false))));
        // line 8
        echo "
    ";
        // line 9
        if ($this->getContext($context, 'verbose')) {
            // line 10
            echo "        ";
            ob_start();
            // line 11
            echo "            ";
            ob_start();
            // line 12
            echo "                <span>PHP ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'collector'), "phpversion", array(), "any", false), "html");
            echo "</span>
                <span style=\"margin: 0; padding: 0; color: #979696;\">|</span>
                <span style=\"color: ";
            // line 14
            echo (($this->getAttribute($this->getContext($context, 'collector'), "hasxdebug", array(), "any", false)) ? ("#759e1a") : ("#a33"));
            echo "\">xdebug</span>
                <span style=\"margin: 0; padding: 0; color: #979696\">|</span>
                <span style=\"color: ";
            // line 16
            echo (($this->getAttribute($this->getContext($context, 'collector'), "hasaccelerator", array(), "any", false)) ? ("#759e1a") : ("#a33"));
            echo "\">accel</span>
            ";
            echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
            // line 18
            echo "        ";
            $context['text'] = new Twig_Markup(ob_get_clean());
            // line 19
            echo "        ";
            $this->env->loadTemplate("WebProfilerBundle:Profiler:toolbar_item.html.twig")->display(array_merge($context, array("link" => false, "icon" => "")));
            // line 20
            echo "    ";
        }
        // line 21
        echo "
    ";
        // line 22
        ob_start();
        // line 23
        echo "        <img width=\"21\" height=\"28\" alt=\"Environment\" style=\"border-width: 0; vertical-align: middle; margin-right: 5px;\" src=\"data:image/png;base64,
        iVBORw0KGgoAAAANSUhEUgAAABUAAAAcCAYAAACOGPReAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAZNJREFUeNpi/P//PwO1ARMDDcCooWDA4+npeRiEQWw0NTweHh4nQZhYORYoLf39+3cbGBuIbyJplPnx44cZjA3ENwjJwQzljoqKOghjo7lGBAcbBLiA+g7B2DBBRqCXj3/79s0CRSUX14lt27a5AplfgNgBCPaDxA8cOOAIokBe9fLy2o1LHxO6BAhAxWTwxIUMPn0seDTCvPotLi7uJIyNIxhQ9OEzVADoRZSgWbRo0UmoF1vx6GPBl06l8XhRmtzEL0KmHF5DWcmUo1E21dLSeo0uCBX7jUffb3z6GIGFdC2QYXPp0iVw4Ovp6T0FUkeA+BUw0c/AZiIwE2QAKTEc+laBktQqIL6al5e3FqqhDsQHYhU8Ln0CzVnY9D1hghYeD5E0PISKfcDjxQ949H2FJX5eJEkY+820adMm4/DiGzz6GFgIeBFX0DzBF/swQ/8oKCi8h7Gh9FeodzikpKSeQ8XuopW12PQxMEKraE0gDoSKrQfi60gaSZaDGQqqCiShks+h5Si8yiBVjnFkNyYAAgwAQGPBFLF65f4AAAAASUVORK5CYII=\"/>
    ";
        $context['icon'] = new Twig_Markup(ob_get_clean());
        // line 26
        echo "    ";
        ob_start();
        // line 27
        echo "        ";
        ob_start();
        // line 28
        echo "            ";
        if ($this->getContext($context, 'verbose')) {
            // line 29
            echo "                <span>";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'collector'), "appname", array(), "any", false), "html");
            echo "</span>
                <span style=\"margin: 0; padding: 0; color: #979696;\">|</span>
                <span>";
            // line 31
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'collector'), "env", array(), "any", false), "html");
            echo "</span>
                <span style=\"margin: 0; padding: 0; color: #979696;\">|</span>
                <span>";
            // line 33
            echo (($this->getAttribute($this->getContext($context, 'collector'), "debug", array(), "any", false)) ? ("debug") : ("no-debug"));
            echo "</span>
                <span style=\"margin: 0; padding: 0; color: #979696;\">|</span>
            ";
        }
        // line 36
        echo "            <span>
                ";
        // line 37
        if ($this->getContext($context, 'profiler_url')) {
            // line 38
            echo "                    <a style=\"color: #2f2f2f\" href=\"";
            echo twig_escape_filter($this->env, $this->getContext($context, 'profiler_url'), "html");
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'collector'), "token", array(), "any", false), "html");
            echo "</a>
                ";
        } else {
            // line 40
            echo "                    ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'collector'), "token", array(), "any", false), "html");
            echo "
                ";
        }
        // line 42
        echo "            </span>
        ";
        echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
        // line 44
        echo "    ";
        $context['text'] = new Twig_Markup(ob_get_clean());
        // line 45
        echo "    ";
        $this->env->loadTemplate("WebProfilerBundle:Profiler:toolbar_item.html.twig")->display(array_merge($context, array("link" => $this->getContext($context, 'profiler_url'))));
    }

    // line 48
    public function block_menu($context, array $blocks = array())
    {
        // line 49
        echo "<span class=\"label\">
    <span class=\"icon\"><img src=\"";
        // line 50
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/webprofiler/images/profiler/config.png"), "html");
        echo "\" alt=\"Configuration\" /></span>
    <strong>Config</strong>
</span>
";
    }

    // line 55
    public function block_panel($context, array $blocks = array())
    {
        // line 56
        echo "    <h2>Project Configuration</h2>
    <table>
        <tr>
            <th>Key</th>
            <th>Value</th>
        </tr>
        <tr>
            <th>Symfony version</th>
            <td>";
        // line 64
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'collector'), "symfonyversion", array(), "any", false), "html");
        echo "</td>
        </tr>
        <tr>
            <th>Application name</th>
            <td>";
        // line 68
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'collector'), "appname", array(), "any", false), "html");
        echo "</td>
        </tr>
        <tr>
            <th>Environment</th>
            <td>";
        // line 72
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'collector'), "env", array(), "any", false), "html");
        echo "</td>
        </tr>
        <tr>
            <th>Debug</th>
            <td>";
        // line 76
        echo (($this->getAttribute($this->getContext($context, 'collector'), "debug", array(), "any", false)) ? ("enabled") : ("disabled"));
        echo "</td>
        </tr>
    </table>

    <h2>PHP configuration</h2>
    <table>
        <tr>
            <th>Key</th>
            <th>Value</th>
        </tr>
        <tr>
            <th>PHP version</th>
            <td>";
        // line 88
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'collector'), "phpversion", array(), "any", false), "html");
        echo "</td>
        </tr>
        <tr>
            <th>Xdebug</th>
            <td>";
        // line 92
        echo (($this->getAttribute($this->getContext($context, 'collector'), "hasxdebug", array(), "any", false)) ? ("enabled") : ("disabled"));
        echo "</td>
        </tr>
        <tr>
            <th>PHP acceleration</th>
            <td>";
        // line 96
        echo (($this->getAttribute($this->getContext($context, 'collector'), "hasaccelerator", array(), "any", false)) ? ("enabled") : ("disabled"));
        echo "</td>
        </tr>
        <tr>
            <th>XCache</th>
            <td>";
        // line 100
        echo (($this->getAttribute($this->getContext($context, 'collector'), "hasxcache", array(), "any", false)) ? ("enabled") : ("disabled"));
        echo "</td>
        </tr>
        <tr>
            <th>APC</th>
            <td>";
        // line 104
        echo (($this->getAttribute($this->getContext($context, 'collector'), "hasapc", array(), "any", false)) ? ("enabled") : ("disabled"));
        echo "</td>
        </tr>
        <tr>
            <th>EAccelerator</th>
            <td>";
        // line 108
        echo (($this->getAttribute($this->getContext($context, 'collector'), "haseaccelerator", array(), "any", false)) ? ("enabled") : ("disabled"));
        echo "</td>
        </tr>
    </table>

    <h2>Active bundles</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>Path</th>
        </tr>
        ";
        // line 118
        $context['bundles'] = $this->getAttribute($this->getContext($context, 'collector'), "bundles", array(), "any", false);
        // line 119
        echo "        ";
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable(twig_sort_filter(twig_get_array_keys_filter($this->getContext($context, 'bundles'))));
        foreach ($context['_seq'] as $context['_key'] => $context['name']) {
            // line 120
            echo "        <tr>
            <th>";
            // line 121
            echo twig_escape_filter($this->env, $this->getContext($context, 'name'), "html");
            echo "</th>
            <td>";
            // line 122
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'bundles'), $this->getContext($context, 'name'), array(), "array", false), "html");
            echo "</td>
        </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['name'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 125
        echo "    </table>

";
    }

    public function getTemplateName()
    {
        return "WebProfilerBundle:Collector:config.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }
}
