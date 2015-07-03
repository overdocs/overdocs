<?xml version="1.0"?>
<xsl:stylesheet version="1.0"
        xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
        xmlns:php="http://php.net/xsl"
        xmlns:o="http://overdocs.net/overdocs"
        exclude-result-prefixes="o php"
        indent="yes">

    <xsl:strip-space elements="o:snippet" />

    <xsl:output method="html"></xsl:output>
    <xsl:template match="/">
        <xsl:apply-templates />
    </xsl:template>

    <xsl:template match="o:sheet">
        <xsl:apply-templates select="*[not(self::o:keywords)]" />
    </xsl:template>

    <xsl:template match="o:sheet/o:summary | o:sheet/o:title"></xsl:template>

    <xsl:template match="o:cheat">
        <section class="cheat">
            <xsl:attribute name="id"><xsl:value-of select="@id" /></xsl:attribute>
            <h3>
                <xsl:value-of select="@title" />
                <xsl:choose>
                    <xsl:when test="@version">
                        <xsl:call-template name="cheat-version"/>
                    </xsl:when>
                </xsl:choose>
                <a class="anchor">
                    <xsl:attribute name="href">#<xsl:value-of select="@id" /></xsl:attribute>
                    #<xsl:value-of select="@id" />
                </a>
            </h3>
            <xsl:apply-templates select="*[not(self::o:version)]" />
        </section>
    </xsl:template>

    <xsl:template name="cheat-version">
        <span class="cheat-version">(<span class="sr-only">Applicable in versions: </span><xsl:value-of select="@version" />)</span>
    </xsl:template>

    <xsl:template match="o:snippet">
        <pre>
            <code>
                <xsl:attribute name="class">language-<xsl:value-of select="@language" /></xsl:attribute>
                <xsl:apply-templates />
            </code>
        </pre>
    </xsl:template>

    <xsl:template match="o:learn-more">
        <section id="learn-more">
            <h3>Learn more <a class="anchor" href="#learn-more">#learn-more</a></h3>
            <ul>
                <xsl:for-each select="o:link | o:sheet-link">
                    <li><xsl:apply-templates select="." /></li>
                </xsl:for-each>
            </ul>
        </section>
    </xsl:template>

    <xsl:template match="o:code">
        <code class="inline">
            <xsl:apply-templates />
        </code>
    </xsl:template>

    <xsl:template match="o:note">
        <div class="note">
            <h4>Note</h4>
            <xsl:apply-templates />
        </div>
    </xsl:template>

    <xsl:template match="o:warning">
        <div class="warning">
            <h4>Warning</h4>
            <xsl:apply-templates />
        </div>
    </xsl:template>

    <xsl:template match="o:p">
        <p>
            <xsl:apply-templates />
        </p>
    </xsl:template>

    <xsl:template match="o:strong">
        <strong><xsl:apply-templates /></strong>
    </xsl:template>

    <xsl:template match="o:em">
        <em><xsl:apply-templates /></em>
    </xsl:template>

    <xsl:template match="o:ul">
        <ul>
            <xsl:apply-templates />
        </ul>
    </xsl:template>

    <xsl:template match="o:ol">
        <ol>
            <xsl:apply-templates />
        </ol>
    </xsl:template>

    <xsl:template match="o:li">
        <li><xsl:apply-templates /></li>
    </xsl:template>

    <xsl:template match="o:table">
        <table>
            <xsl:apply-templates />
        </table>
    </xsl:template>

    <xsl:template match="o:table/o:caption">
        <caption><xsl:apply-templates /></caption>
    </xsl:template>

    <xsl:template match="o:table/o:tr">
        <tr><xsl:apply-templates /></tr>
    </xsl:template>

    <xsl:template match="o:tr/o:td">
        <xsl:choose>
            <xsl:when test="@colspan">
                <td><xsl:attribute name="colspan"><xsl:value-of select="@colspan" /></xsl:attribute><xsl:apply-templates /></td>
            </xsl:when>
            <xsl:otherwise>
                <td><xsl:apply-templates /></td>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>

    <xsl:template match="o:tr/o:th">
        <xsl:choose>
            <xsl:when test="@colspan">
                <th><xsl:attribute name="colspan"><xsl:value-of select="@colspan" /></xsl:attribute><xsl:apply-templates /></th>
            </xsl:when>
            <xsl:otherwise>
                <th><xsl:apply-templates /></th>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>

    <xsl:template match="o:image">
        <figure>
            <img alt="">
                <xsl:attribute name="src"><xsl:value-of select="php:function('OverDocs\SheetParser::canonicalize', concat($images_url, @src))" /></xsl:attribute>
                <xsl:choose>
                    <!-- Do not change order of @alt and @title conditions -->
                    <xsl:when test="@alt">
                        <xsl:attribute name="alt"><xsl:value-of select="@alt" /></xsl:attribute>
                    </xsl:when>
                    <xsl:when test="@title">
                        <xsl:attribute name="alt"><xsl:value-of select="@title" /></xsl:attribute>
                    </xsl:when>
                </xsl:choose>
                <xsl:choose>
                    <xsl:when test="@height">
                        <xsl:attribute name="height"><xsl:value-of select="@height" /></xsl:attribute>
                    </xsl:when>
                </xsl:choose>
                <xsl:choose>
                    <xsl:when test="@width">
                        <xsl:attribute name="width"><xsl:value-of select="@width" /></xsl:attribute>
                    </xsl:when>
                </xsl:choose>
            </img>
            <xsl:choose>
                <xsl:when test="@title">
                    <figcaption><xsl:value-of select="@title" /></figcaption>
                </xsl:when>
            </xsl:choose>
        </figure>
    </xsl:template>

    <xsl:template match="o:abbr">
        <abbr><xsl:attribute name="title"><xsl:value-of select="php:function('OverDocs\SheetParser::parseAbbreviation', node())" /></xsl:attribute><xsl:apply-templates /></abbr>
    </xsl:template>

    <xsl:template match="o:link">
        <a href="" class="external-link"><xsl:attribute name="href"><xsl:value-of select="@href" /></xsl:attribute><xsl:apply-templates /></a>
    </xsl:template>

    <xsl:template match="o:sheet-link">
        <a href="" class="link-sheet">
            <xsl:attribute name="href"><xsl:value-of select="$base_url" />/<xsl:value-of select="@category" />/<xsl:value-of select="@id" /></xsl:attribute>
            <xsl:apply-templates />
        </a>
    </xsl:template>

</xsl:stylesheet>
