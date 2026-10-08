<?php
/*
Template Name: Botphonic CTA Builder
*/
get_header();

/*
 * assets/css/cta.css is enqueued for this template in inc/function-enqueue.php
 * now, not here. The call used to sit on the line below get_header(), which has
 * already run wp_head() by then, so WordPress had no head left to print into
 * and deferred the stylesheet to the footer: the page painted its previews
 * unstyled and then restyled them. It also passed rand() as the version, so no
 * visitor ever got a cached copy.
 */
?>
<style>
/* ── Builder UI Tokens ──
   Chrome for the builder shell only: the sidebar, the cards and the little
   field rows. Everything these style is a .bpb-* element.

   --r-sm/md/lg (7/11/16px) used to be declared here too and were never in
   effect: cta.css declares the same three names at :root and, because it was
   being deferred to the footer, it landed after this block and won. The whole
   page — builder chrome and the CTA previews alike — has always rendered with
   cta.css's 8/12/18px. Those three are gone rather than kept, so the previews
   keep showing production radii, which is the one thing a component gallery
   has to get right. The radius scale now lives in style.css.

   If the builder shell is ever meant to have its own tighter radii, give them a
   --bpb- prefix and scope them to .bpb-layout; do not reintroduce bare --r-*
   names here, because cta.css owns those on this page. */
.bpb-layout{
  --bp:#2563EB;--bp-d:#1D4ED8;--bp-pale:#EFF6FF;
  --bp-purple:#7C3AED;
  --bp-green:#10B981;
  --surf:#F8FAFC;--bdr:#E2E8F0;--bdr-mid:#CBD5E1;
  --tx:#0F172A;--tx-mid:#475569;--tx-soft:#94A3B8;
}

/* layout */
.bpb-layout{display:flex;height:calc(100vh - 50px);overflow:hidden}
.bpb-sidebar{width:210px;background:#fff;border-right:1px solid var(--bdr);overflow-y:auto;flex-shrink:0}
.bpb-sb-sec{padding:10px 0 4px}
.bpb-sb-lbl{font-size:9px;font-weight:700;letter-spacing:.13em;text-transform:uppercase;color:var(--tx-soft);padding:0 14px 4px}
.bpb-sb-item{display:flex;align-items:center;gap:7px;padding:6px 14px;font-size:11.5px;font-weight:500;color:var(--tx);text-decoration:none;border-left:3px solid transparent;transition:all .11s}
.bpb-sb-item:hover{background:var(--surf);color:var(--bp)}
.bpb-sb-item.active{background:var(--bp-pale);color:var(--bp);border-left-color:var(--bp);font-weight:700}
.bpb-sb-dot{width:6px;height:6px;border-radius:50%;flex-shrink:0}
.bpb-main{flex:1;overflow-y:auto;padding:20px 20px 60px}
.bpb-sec-div{display:flex;align-items:center;gap:10px;margin-bottom:16px;margin-top:4px}
.bpb-sec-div span{font-size:9px;font-weight:700;letter-spacing:.13em;text-transform:uppercase;color:var(--tx-soft);white-space:nowrap}
.bpb-sec-div::before,.bpb-sec-div::after{content:'';flex:1;height:1px;background:var(--bdr)}

/* card */
.bpb-card{background:#fff;border-radius:var(--r-lg);border:1.5px solid var(--bdr);overflow:hidden;margin-bottom:18px}
.bpb-card__hdr{display:flex;align-items:center;justify-content:space-between;padding:9px 14px;border-bottom:1px solid var(--bdr);gap:10px}
.bpb-card__meta{display:flex;align-items:center;gap:7px;min-width:0;flex-wrap:wrap}
.bpb-card__idx{font-size:9.5px;font-weight:700;letter-spacing:.08em;background:var(--surf);color:var(--tx-soft);padding:2px 7px;border-radius:4px;flex-shrink:0}
.bpb-card__name{font-size:12px;font-weight:700;color:var(--tx)}
.bpb-og{background:#ECFDF5;color:#065F46;border:1px solid #A7F3D0;border-radius:4px;padding:1px 7px;font-size:9.5px;font-weight:700;letter-spacing:.03em}
.bpb-card__acts{display:flex;gap:5px;flex-shrink:0}
.xbtn{font-size:10.5px;font-weight:600;padding:4px 11px;border-radius:5px;border:none;cursor:pointer;transition:all .11s;line-height:1;white-space:nowrap;font-family:inherit}
.xbtn--e{background:var(--surf);color:var(--tx-mid)}.xbtn--e:hover{background:var(--bdr-mid)}
.xbtn--c{background:var(--bp-pale);color:var(--bp)}.xbtn--c:hover{background:#dbeafe}
.xbtn--p{background:var(--bp);color:#fff}.xbtn--p:hover{background:var(--bp-d)}.xbtn--p.ok{background:var(--bp-green)}

/* edit panel */
.bpb-ep{display:none;padding:12px 14px;background:#fafbff;border-bottom:1px solid var(--bdr)}
.bpb-ep.open{display:block}
.bpb-ep-rows{display:flex;flex-wrap:wrap;gap:9px}
.xf{display:flex;flex-direction:column;gap:3px;min-width:140px;flex:1}
.xf--w{flex:2;min-width:240px}.xf--f{flex:1 0 100%}
.xf label{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--tx-soft)}
.xf input,.xf textarea{font-size:12px;color:var(--tx);padding:5px 8px;border:1.5px solid var(--bdr);border-radius:5px;outline:none;transition:border-color .11s;background:#fff;width:100%;font-family:inherit}
.xf input:focus,.xf textarea:focus{border-color:var(--bp)}
.xf textarea{resize:vertical;min-height:48px;line-height:1.5}

/* dynamic rows */
.xdyn{flex:1 0 100%;display:flex;flex-direction:column;gap:5px}
.xdyn__lbl{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--tx-soft);margin-bottom:1px;display:flex;align-items:center;justify-content:space-between}
.xdyn-row{display:flex;gap:5px;align-items:center}
.xdyn-row input{font-size:12px;color:var(--tx);padding:5px 8px;border:1.5px solid var(--bdr);border-radius:5px;outline:none;background:#fff;flex:1;transition:border-color .11s;font-family:inherit}
.xdyn-row input:focus{border-color:var(--bp)}
.xdyn-row input.url{min-width:200px}
.xadd,.xrm{font-size:10px;font-weight:600;padding:3px 9px;border-radius:4px;border:none;cursor:pointer;line-height:1;transition:all .11s;white-space:nowrap;font-family:inherit}
.xadd{background:var(--bp-pale);color:var(--bp)}.xadd:hover{background:#dbeafe}
.xrm{background:#FEE2E2;color:#DC2626;padding:3px 7px}.xrm:hover{background:#FECACA}

/* preview */
.bpb-card__preview{padding:18px;background:var(--surf)}

/* code */
.bpb-codepane{display:none;border-top:1px solid var(--bdr)}
.bpb-codepane.open{display:block}
.bpb-code-hdr{display:flex;align-items:center;justify-content:space-between;padding:6px 12px;background:#0F172A}
.bpb-code-hdr span{font-size:9px;font-weight:600;letter-spacing:.08em;color:rgba(255,255,255,.3)}
.bpb-code-cp{font-size:10.5px;font-weight:600;padding:3px 10px;border-radius:4px;border:1px solid rgba(255,255,255,.18);background:transparent;color:rgba(255,255,255,.55);cursor:pointer;transition:all .11s;font-family:inherit}
.bpb-code-cp:hover{background:rgba(255,255,255,.08);color:#fff}.bpb-code-cp.ok{border-color:var(--bp-green);color:var(--bp-green)}
.bpb-code-body{background:#0B1120;padding:14px 16px;max-height:280px;overflow:auto}
.bpb-code-body pre{font-family:var(--mono-font);font-size:11px;line-height:1.7;color:#C8D3E8;white-space:pre}

/* toast */
.bpb-toast{position:fixed;bottom:22px;left:50%;transform:translateX(-50%) translateY(10px);background:#0F172A;color:#fff;font-size:12.5px;font-weight:600;padding:8px 18px;border-radius:8px;opacity:0;pointer-events:none;transition:all .2s;z-index:9999;white-space:nowrap;font-family:inherit}
.bpb-toast.show{opacity:1;transform:translateX(-50%) translateY(0)}
</style>

<div class="bpb-layout">
  <nav class="bpb-sidebar">
    <div class="bpb-sb-sec">
      <div class="bpb-sb-lbl">CTA Templates</div>
      <a class="bpb-sb-item active" href="#bpb-01"><span class="bpb-sb-dot" style="background:#6366F1"></span>01 Pulse Banner</a>
      <a class="bpb-sb-item" href="#bpb-02"><span class="bpb-sb-dot" style="background:#2563EB"></span>02 Metric Punch</a>
      <a class="bpb-sb-item" href="#bpb-03"><span class="bpb-sb-dot" style="background:#7C3AED"></span>03 Spotlight Card</a>
      <a class="bpb-sb-item" href="#bpb-04"><span class="bpb-sb-dot" style="background:#0F172A"></span>04 Switch Strip</a>
      <a class="bpb-sb-item" href="#bpb-05"><span class="bpb-sb-dot" style="background:#06B6D4"></span>05 Value Grid</a>
      <a class="bpb-sb-item" href="#bpb-06"><span class="bpb-sb-dot" style="background:#F97316"></span>06 Orange Ignite</a>
      <a class="bpb-sb-item" href="#bpb-07"><span class="bpb-sb-dot" style="background:#94A3B8"></span>07 Minimal Nudge</a>
      <a class="bpb-sb-item" href="#bpb-08"><span class="bpb-sb-dot" style="background:#1E1B4B"></span>08 Deep End</a>
      <a class="bpb-sb-item" href="#bpb-09"><span class="bpb-sb-dot" style="background:#E2E8F0"></span>09 Slash Compare</a>
      <a class="bpb-sb-item" href="#bpb-10"><span class="bpb-sb-dot" style="background:#2563EB"></span>10 Booking Widget</a>
      <a class="bpb-sb-item" href="#bpb-11"><span class="bpb-sb-dot" style="background:#10B981"></span>11 Trust Ribbon</a>
    </div>
    <div class="bpb-sb-sec">
      <div class="bpb-sb-lbl">Blog Components</div>
      <a class="bpb-sb-item" href="#bpb-lmf"><span class="bpb-sb-dot" style="background:var(--bp)"></span>Learn More (Full)</a>
      <a class="bpb-sb-item" href="#bpb-lml"><span class="bpb-sb-dot" style="background:var(--bp)"></span>Learn More (Link)</a>
      <a class="bpb-sb-item" href="#bpb-note"><span class="bpb-sb-dot" style="background:#FDE68A"></span>Note Box</a>
      <a class="bpb-sb-item" href="#bpb-tip"><span class="bpb-sb-dot" style="background:#6EE7B7"></span>Pro Tip Box</a>
    </div>
  </nav>

  <main class="bpb-main">
    <div class="bpb-sec-div"><span>CTA Templates</span></div>

    <!---- CTA 01 - PULSE BANNER ---->
    <div class="bpb-card" id="bpb-01">
      <div class="bpb-card__hdr">
        <div class="bpb-card__meta"><span class="bpb-card__idx">01</span><span class="bpb-card__name">Pulse Banner - .cta-pulse-banner</span><span class="bpb-og">original class</span></div>
        <div class="bpb-card__acts">
          <button class="xbtn xbtn--e" onclick="xep('ep01')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M8.5 1.5L10.5 3.5L4 10H2V8L8.5 1.5Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"></path></svg>Edit</button>
          <button class="xbtn xbtn--c" onclick="xcode('cp01','pv01')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M4 3L1 6L4 9M8 3L11 6L8 9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"></path></svg>Code</button>
          <button class="xbtn xbtn--p" id="hc01" onclick="xcopy('pv01','hc01')">Copy Code</button>
        </div>
      </div>
      <div class="bpb-ep" id="ep01">
        <div class="bpb-ep-rows">
          <div class="xf"><label>Live Indicator Text</label><input id="f01-live" value="AI answering calls right now" oninput="u01()"></div>
          <div class="xf xf--w"><label>Headline</label><input id="f01-hl" value="Stop Losing Bookings to Missed Calls" oninput="u01()"></div>
          <div class="xf xf--f"><label>Subtext</label><textarea id="f01-sub" oninput="u01()">Botphonic answers, qualifies, and schedules - 24/7, with zero hold time.</textarea></div>
          <div class="xdyn">
            <div class="xdyn__lbl">Buttons <button class="xadd" onclick="xAddBtn('btn01-rows','u01')">+ Add</button></div>
            <div id="btn01-rows">
              <div class="xdyn-row"><input placeholder="Button text" value="Start Free" oninput="u01()"><input class="url" placeholder="https://..." value="https://app.botphonic.ai/register/" oninput="u01()"><input placeholder="Class (e.g. bp-btn-primary)" value="bp-btn button bp-btn-primary" oninput="u01()"><button class="xrm" onclick="xRm(this,'btn01-rows',u01)">✕</button></div>
              <div class="xdyn-row"><input placeholder="Button text" value="See Demo" oninput="u01()"><input class="url" placeholder="https://..." value="https://calendly.com/contact-botphonic/product-discovery" oninput="u01()"><input placeholder="Class" value="bp-btn button bp-btn-outline-white" oninput="u01()"><button class="xrm" onclick="xRm(this,'btn01-rows',u01)">✕</button></div>
            </div>
          </div>
        </div>
      </div>
      <div class="bpb-card__preview"><div id="pv01">
        <div class="cta-wrap" style="padding-top:0"><div class="cta-block"><div class="cta-pulse-banner">
          <div>
            <div class="pb-live"><div class="dot"></div><span id="l01-live">AI answering calls right now</span></div>
            <h4 id="l01-hl">Stop Losing Bookings to Missed Calls</h4>
            <p id="l01-sub">Botphonic answers, qualifies, and schedules - 24/7, with zero hold time.</p>
          </div>
          <div class="pb-actions" id="l01-btns"></div>
        </div></div></div>
      </div></div>
      <div class="bpb-codepane" id="cp01"><div class="bpb-code-hdr"><span>HTML - paste into your page</span><button class="bpb-code-cp" id="pc01" onclick="xpre('pre01','pc01')">Copy</button></div><div class="bpb-code-body"><pre id="pre01"></pre></div></div>
    </div>

    <!---- CTA 02 - METRIC PUNCH ---->
    <div class="bpb-card" id="bpb-02">
      <div class="bpb-card__hdr">
        <div class="bpb-card__meta"><span class="bpb-card__idx">02</span><span class="bpb-card__name">Metric Punch - .cta-metric-punch</span><span class="bpb-og">original class</span></div>
        <div class="bpb-card__acts">
          <button class="xbtn xbtn--e" onclick="xep('ep02')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M8.5 1.5L10.5 3.5L4 10H2V8L8.5 1.5Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"></path></svg>Edit</button>
          <button class="xbtn xbtn--c" onclick="xcode('cp02','pv02')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M4 3L1 6L4 9M8 3L11 6L8 9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"></path></svg>Code</button>
          <button class="xbtn xbtn--p" id="hc02" onclick="xcopy('pv02','hc02')">Copy Code</button>
        </div>
      </div>
      <div class="bpb-ep" id="ep02">
        <div class="bpb-ep-rows">
          <div class="xf xf--w"><label>Headline</label><input id="f02-hl" value="These Are Real Results From Real Businesses" oninput="u02()"></div>
          <div class="xf xf--f"><label>Subtext</label><textarea id="f02-sub" oninput="u02()">Join 500+ teams using Botphonic to automate appointments without losing the human touch.</textarea></div>
          <div class="xf"><label>Button Text</label><input id="f02-bt" value="Get Started Free" oninput="u02()"></div>
          <div class="xf xf--w"><label>Button URL</label><input id="f02-bl" value="https://app.botphonic.ai/register/" oninput="u02()"></div>
          <div class="xf"><label>Trust Text</label><input id="f02-trust" value="No credit card required" oninput="u02()"></div>
          <div class="xdyn">
            <div class="xdyn__lbl">Stats (Number · Label) <button class="xadd" onclick="xAddStat('stat02-rows','u02')">+ Add</button></div>
            <div id="stat02-rows">
              <div class="xdyn-row"><input placeholder="2.5×" value="2.5×" oninput="u02()" style="max-width:70px"><input placeholder="More Bookings" value="More Bookings" oninput="u02()"><button class="xrm" onclick="xRm(this,'stat02-rows',u02)">✕</button></div>
              <div class="xdyn-row"><input placeholder="40%" value="40%" oninput="u02()" style="max-width:70px"><input placeholder="Fewer No-Shows" value="Fewer No-Shows" oninput="u02()"><button class="xrm" onclick="xRm(this,'stat02-rows',u02)">✕</button></div>
              <div class="xdyn-row"><input placeholder="24/7" value="24/7" oninput="u02()" style="max-width:70px"><input placeholder="Availability" value="Availability" oninput="u02()"><button class="xrm" onclick="xRm(this,'stat02-rows',u02)">✕</button></div>
              <div class="xdyn-row"><input placeholder="60%" value="60%" oninput="u02()" style="max-width:70px"><input placeholder="Cost Reduction" value="Cost Reduction" oninput="u02()"><button class="xrm" onclick="xRm(this,'stat02-rows',u02)">✕</button></div>
            </div>
          </div>
        </div>
      </div>
      <div class="bpb-card__preview"><div id="pv02">
        <div class="cta-wrap" style="padding-top:0"><div class="cta-block"><div class="cta-metric-punch">
          <div>
            <div class="mp-numbers" id="l02-stats"></div>
            <div class="mp-copy">
              <h4 id="l02-hl">These Are Real Results From Real Businesses</h4>
              <p id="l02-sub">Join 500+ teams using Botphonic to automate appointments without losing the human touch.</p>
            </div>
          </div>
          <div class="mp-right">
            <a id="l02-btn" href="https://app.botphonic.ai/register/" class="bp-btn button bp-btn-primary bp-btn-lg">Get Started Free <svg class="arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"></path></svg></a>
            <div class="mp-trust"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="icon-check"><polyline points="20 6 9 17 4 12"></polyline></svg><span id="l02-trust">No credit card required</span></div>
          </div>
        </div></div></div>
      </div></div>
      <div class="bpb-codepane" id="cp02"><div class="bpb-code-hdr"><span>HTML</span><button class="bpb-code-cp" id="pc02" onclick="xpre('pre02','pc02')">Copy</button></div><div class="bpb-code-body"><pre id="pre02"></pre></div></div>
    </div>

    <!---- CTA 03 - SPOTLIGHT CARD ---->
    <div class="bpb-card" id="bpb-03">
      <div class="bpb-card__hdr">
        <div class="bpb-card__meta"><span class="bpb-card__idx">03</span><span class="bpb-card__name">Spotlight Card - .cta-spotlight-card</span><span class="bpb-og">original class</span></div>
        <div class="bpb-card__acts">
          <button class="xbtn xbtn--e" onclick="xep('ep03')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M8.5 1.5L10.5 3.5L4 10H2V8L8.5 1.5Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"></path></svg>Edit</button>
          <button class="xbtn xbtn--c" onclick="xcode('cp03','pv03')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M4 3L1 6L4 9M8 3L11 6L8 9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"></path></svg>Code</button>
          <button class="xbtn xbtn--p" id="hc03" onclick="xcopy('pv03','hc03')">Copy Code</button>
        </div>
      </div>
      <div class="bpb-ep" id="ep03">
        <div class="bpb-ep-rows">
          <div class="xf"><label>Eyebrow</label><input id="f03-eye" value="Free · No Credit Card" oninput="u03()"></div>
          <div class="xf xf--w"><label>Headline</label><input id="f03-hl" value="Let AI Handle Your Bookings While You Sleep" oninput="u03()"></div>
          <div class="xf xf--f"><label>Subtext</label><textarea id="f03-sub" oninput="u03()">Botphonic's voice AI picks up every call, qualifies the lead, and schedules the meeting - automatically, around the clock.</textarea></div>
          <div class="xf"><label>Button 1 Text</label><input id="f03-b1t" value="Start Booking Automatically" oninput="u03()"></div>
          <div class="xf xf--w"><label>Button 1 URL</label><input id="f03-b1l" value="https://app.botphonic.ai/register/" oninput="u03()"></div>
          <div class="xf"><label>Button 2 Text</label><input id="f03-b2t" value="Watch a Demo" oninput="u03()"></div>
          <div class="xf xf--w"><label>Button 2 URL</label><input id="f03-b2l" value="https://calendly.com/contact-botphonic/product-discovery" oninput="u03()"></div>
          <div class="xdyn">
            <div class="xdyn__lbl">Footer Ticks <button class="xadd" onclick="xAddTick('tick03-rows','u03')">+ Add</button></div>
            <div id="tick03-rows">
              <div class="xdyn-row"><input value="No setup fee" oninput="u03()"><button class="xrm" onclick="xRm(this,'tick03-rows',u03)">✕</button></div>
              <div class="xdyn-row"><input value="HIPAA &amp; SOC2" oninput="u03()"><button class="xrm" onclick="xRm(this,'tick03-rows',u03)">✕</button></div>
              <div class="xdyn-row"><input value="Live in 48 hours" oninput="u03()"><button class="xrm" onclick="xRm(this,'tick03-rows',u03)">✕</button></div>
            </div>
          </div>
        </div>
      </div>
      <div class="bpb-card__preview"><div id="pv03">
        <div class="cta-wrap" style="padding-top:0"><div class="cta-block"><div class="cta-spotlight-card">
          <div class="sc-eyebrow"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg><span id="l03-eye">Free · No Credit Card</span></div>
          <h3 id="l03-hl">Let AI Handle Your Bookings <em>While You Sleep</em></h3>
          <p id="l03-sub">Botphonic's voice AI picks up every call, qualifies the lead, and schedules the meeting - automatically, around the clock.</p>
          <div class="sc-btns">
            <a id="l03-b1" href="https://app.botphonic.ai/register/" class="bp-btn button bp-btn-primary bp-btn-lg">Start Booking Automatically <svg class="arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"></path></svg></a>
            <a id="l03-b2" href="https://calendly.com/contact-botphonic/product-discovery" class="bp-btn button bp-btn-outline-blue bp-btn-lg">Watch a Demo</a>
          </div>
          <div class="sc-footer" id="l03-ticks"></div>
        </div></div></div>
      </div></div>
      <div class="bpb-codepane" id="cp03"><div class="bpb-code-hdr"><span>HTML</span><button class="bpb-code-cp" id="pc03" onclick="xpre('pre03','pc03')">Copy</button></div><div class="bpb-code-body"><pre id="pre03"></pre></div></div>
    </div>

    <!---- CTA 04 - SWITCH STRIP ---->
    <div class="bpb-card" id="bpb-04">
      <div class="bpb-card__hdr">
        <div class="bpb-card__meta"><span class="bpb-card__idx">04</span><span class="bpb-card__name">Switch Strip - .cta-switch-strip</span><span class="bpb-og">original class</span></div>
        <div class="bpb-card__acts">
          <button class="xbtn xbtn--e" onclick="xep('ep04')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M8.5 1.5L10.5 3.5L4 10H2V8L8.5 1.5Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"></path></svg>Edit</button>
          <button class="xbtn xbtn--c" onclick="xcode('cp04','pv04')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M4 3L1 6L4 9M8 3L11 6L8 9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"></path></svg>Code</button>
          <button class="xbtn xbtn--p" id="hc04" onclick="xcopy('pv04','hc04')">Copy Code</button>
        </div>
      </div>
      <div class="bpb-ep" id="ep04">
        <div class="bpb-ep-rows">
          <div class="xf"><label>Badge</label><input id="f04-badge" value="Easy Migration" oninput="u04()"></div>
          <div class="xf xf--w"><label>Headline</label><input id="f04-hl" value="Switch to Botphonic in Under 48 Hours" oninput="u04()"></div>
          <div class="xf xf--f"><label>Subtext</label><textarea id="f04-sub" oninput="u04()">Keep your numbers, ditch the per-minute fees. Most teams are live before their next billing cycle.</textarea></div>
          <div class="xf"><label>Button 1 Text</label><input id="f04-b1t" value="Start Switching" oninput="u04()"></div>
          <div class="xf xf--w"><label>Button 1 URL</label><input id="f04-b1l" value="https://app.botphonic.ai/register/" oninput="u04()"></div>
          <div class="xf"><label>Button 2 Text</label><input id="f04-b2t" value="Talk to Sales" oninput="u04()"></div>
          <div class="xf xf--w"><label>Button 2 URL</label><input id="f04-b2l" value="https://calendly.com/contact-botphonic/product-discovery" oninput="u04()"></div>
          <div class="xf xf--f"><label>Footer note</label><input id="f04-note" value="Migration support included" oninput="u04()"></div>
        </div>
      </div>
      <div class="bpb-card__preview"><div id="pv04">
        <div class="cta-wrap" style="padding-top:0"><div class="cta-block"><div class="cta-switch-strip">
          <div class="ss-left">
            <div class="ss-badge"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg><span id="l04-badge">Easy Migration</span></div>
            <h4 id="l04-hl">Switch to Botphonic in Under 48 Hours</h4>
            <p id="l04-sub">Keep your numbers, ditch the per-minute fees. Most teams are live before their next billing cycle.</p>
          </div>
          <div class="ss-right">
            <a id="l04-b1" href="https://app.botphonic.ai/register/" class="bp-btn button bp-btn-primary">Start Switching <svg class="arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"></path></svg></a>
            <a id="l04-b2" href="https://calendly.com/contact-botphonic/product-discovery" class="bp-btn button bp-btn-outline-white bp-btn-sm">Talk to Sales</a>
            <div class="migrate-text" id="l04-note">Migration support included</div>
          </div>
        </div></div></div>
      </div></div>
      <div class="bpb-codepane" id="cp04"><div class="bpb-code-hdr"><span>HTML</span><button class="bpb-code-cp" id="pc04" onclick="xpre('pre04','pc04')">Copy</button></div><div class="bpb-code-body"><pre id="pre04"></pre></div></div>
    </div>

    <!---- CTA 05 - VALUE GRID ---->
    <div class="bpb-card" id="bpb-05">
      <div class="bpb-card__hdr">
        <div class="bpb-card__meta"><span class="bpb-card__idx">05</span><span class="bpb-card__name">Value Grid - .cta-value-grid</span><span class="bpb-og">original class</span></div>
        <div class="bpb-card__acts">
          <button class="xbtn xbtn--e" onclick="xep('ep05')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M8.5 1.5L10.5 3.5L4 10H2V8L8.5 1.5Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"></path></svg>Edit</button>
          <button class="xbtn xbtn--c" onclick="xcode('cp05','pv05')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M4 3L1 6L4 9M8 3L11 6L8 9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"></path></svg>Code</button>
          <button class="xbtn xbtn--p" id="hc05" onclick="xcopy('pv05','hc05')">Copy Code</button>
        </div>
      </div>
      <div class="bpb-ep" id="ep05">
        <div class="bpb-ep-rows">
          <div class="xf xf--w"><label>Headline</label><input id="f05-hl" value="Everything You Need to Automate Customer Calls" oninput="u05()"></div>
          <div class="xf xf--f"><label>Subtext</label><textarea id="f05-sub" oninput="u05()">One platform. No per-minute fees. Deploy in 48 hours.</textarea></div>
          <div class="xdyn">
            <div class="xdyn__lbl">Features (Title · Description) <button class="xadd" onclick="xAddFeat('feat05-rows','u05')">+ Add</button></div>
            <div id="feat05-rows">
              <div class="xdyn-row"><input placeholder="Feature title" value="AI Voice Calling" oninput="u05()"><input placeholder="Description" value="Human-sounding AI picks up every call, 24/7 - no hold music, no missed leads." oninput="u05()"><button class="xrm" onclick="xRm(this,'feat05-rows',u05)">✕</button></div>
              <div class="xdyn-row"><input placeholder="Feature title" value="Smart Scheduling" oninput="u05()"><input placeholder="Description" value="Syncs with your calendar to book, reschedule, and send reminders automatically." oninput="u05()"><button class="xrm" onclick="xRm(this,'feat05-rows',u05)">✕</button></div>
              <div class="xdyn-row"><input placeholder="Feature title" value="Live Analytics" oninput="u05()"><input placeholder="Description" value="Track call volume, conversion rate, and cost per contact in real time." oninput="u05()"><button class="xrm" onclick="xRm(this,'feat05-rows',u05)">✕</button></div>
            </div>
          </div>
          <div class="xdyn">
            <div class="xdyn__lbl">Trust Items <button class="xadd" onclick="xAddTick('trust05-rows','u05')">+ Add</button></div>
            <div id="trust05-rows">
              <div class="xdyn-row"><input value="SOC2 Certified" oninput="u05()"><button class="xrm" onclick="xRm(this,'trust05-rows',u05)">✕</button></div>
              <div class="xdyn-row"><input value="HIPAA Compliant" oninput="u05()"><button class="xrm" onclick="xRm(this,'trust05-rows',u05)">✕</button></div>
              <div class="xdyn-row"><input value="50+ Integrations" oninput="u05()"><button class="xrm" onclick="xRm(this,'trust05-rows',u05)">✕</button></div>
            </div>
          </div>
          <div class="xdyn">
            <div class="xdyn__lbl">Buttons <button class="xadd" onclick="xAddBtn('btn05-rows','u05')">+ Add</button></div>
            <div id="btn05-rows">
              <div class="xdyn-row"><input value="Start Free Trial" oninput="u05()"><input class="url" value="https://app.botphonic.ai/register/" oninput="u05()"><input placeholder="Class" value="bp-btn button bp-btn-primary" oninput="u05()"><button class="xrm" onclick="xRm(this,'btn05-rows',u05)">✕</button></div>
              <div class="xdyn-row"><input value="See Pricing" oninput="u05()"><input class="url" value="https://botphonic.ai/pricings-plans/" oninput="u05()"><input placeholder="Class" value="bp-btn button bp-btn-ghost" oninput="u05()"><button class="xrm" onclick="xRm(this,'btn05-rows',u05)">✕</button></div>
            </div>
          </div>
        </div>
      </div>
      <div class="bpb-card__preview"><div id="pv05">
        <div class="cta-wrap" style="padding-top:0"><div class="cta-block"><div class="cta-value-grid">
          <div class="vg-top"><h3 id="l05-hl">Everything You Need to Automate Customer Calls</h3><p id="l05-sub">One platform. No per-minute fees. Deploy in 48 hours.</p></div>
          <div class="vg-features" id="l05-feats"></div>
          <div class="vg-bottom">
            <div class="vg-trust" id="l05-trust"></div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;" id="l05-btns"></div>
          </div>
        </div></div></div>
      </div></div>
      <div class="bpb-codepane" id="cp05"><div class="bpb-code-hdr"><span>HTML</span><button class="bpb-code-cp" id="pc05" onclick="xpre('pre05','pc05')">Copy</button></div><div class="bpb-code-body"><pre id="pre05"></pre></div></div>
    </div>

    <!---- CTA 06 - ORANGE IGNITE ---->
    <div class="bpb-card" id="bpb-06">
      <div class="bpb-card__hdr">
        <div class="bpb-card__meta"><span class="bpb-card__idx">06</span><span class="bpb-card__name">Orange Ignite - .cta-orange-ignite</span><span class="bpb-og">original class</span></div>
        <div class="bpb-card__acts">
          <button class="xbtn xbtn--e" onclick="xep('ep06')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M8.5 1.5L10.5 3.5L4 10H2V8L8.5 1.5Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"></path></svg>Edit</button>
          <button class="xbtn xbtn--c" onclick="xcode('cp06','pv06')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M4 3L1 6L4 9M8 3L11 6L8 9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"></path></svg>Code</button>
          <button class="xbtn xbtn--p" id="hc06" onclick="xcopy('pv06','hc06')">Copy Code</button>
        </div>
      </div>
      <div class="bpb-ep" id="ep06">
        <div class="bpb-ep-rows">
          <div class="xf xf--w"><label>Tag / Eyebrow</label><input id="f06-tag" value="Don't Miss Another Reservation" oninput="u06()"></div>
          <div class="xf xf--w"><label>Headline</label><input id="f06-hl" value="Your Next Empty Table Costs More Than AI Does" oninput="u06()"></div>
          <div class="xf xf--f"><label>Subtext</label><textarea id="f06-sub" oninput="u06()">Botphonic handles reservations, delivery updates, and customer questions - so your staff can focus on the experience.</textarea></div>
          <div class="xf"><label>Button Text</label><input id="f06-bt" value="Start Free Trial" oninput="u06()"></div>
          <div class="xf xf--w"><label>Button URL</label><input id="f06-bl" value="https://app.botphonic.ai/register/" oninput="u06()"></div>
          <div class="xf xf--f"><label>Note (below button)</label><input id="f06-note" value="No credit card · Cancel anytime" oninput="u06()"></div>
        </div>
      </div>
      <div class="bpb-card__preview"><div id="pv06">
        <div class="cta-wrap" style="padding-top:0"><div class="cta-block"><div class="cta-orange-ignite">
          <div class="oi-inner">
            <div class="oi-icon-wrap"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M8.56 2.9A7 7 0 0119 9v4l1.38 2.76A1 1 0 0119.46 17H4.54a1 1 0 01-.92-1.24L5 13V9a7 7 0 013.56-6.1"></path><path d="M10.68 21a2 2 0 003.64 0"></path></svg></div>
            <div class="oi-copy">
              <div class="oi-tag" id="l06-tag">Don't Miss Another Reservation</div>
              <h4 id="l06-hl">Your Next Empty Table Costs More Than AI Does</h4>
              <p id="l06-sub">Botphonic handles reservations, delivery updates, and customer questions - so your staff can focus on the experience.</p>
            </div>
            <div class="oi-actions">
              <a id="l06-btn" href="https://app.botphonic.ai/register/" class="bp-btn button bp-btn-orange bp-btn-lg">Start Free Trial <svg class="arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"></path></svg></a>
              <div class="no-cc" id="l06-note">No credit card · Cancel anytime</div>
            </div>
          </div>
        </div></div></div>
      </div></div>
      <div class="bpb-codepane" id="cp06"><div class="bpb-code-hdr"><span>HTML</span><button class="bpb-code-cp" id="pc06" onclick="xpre('pre06','pc06')">Copy</button></div><div class="bpb-code-body"><pre id="pre06"></pre></div></div>
    </div>

    <!---- CTA 07 - MINIMAL NUDGE ---->
    <div class="bpb-card" id="bpb-07">
      <div class="bpb-card__hdr">
        <div class="bpb-card__meta"><span class="bpb-card__idx">07</span><span class="bpb-card__name">Minimal Nudge - .cta-minimal-nudge</span><span class="bpb-og">original class</span></div>
        <div class="bpb-card__acts">
          <button class="xbtn xbtn--e" onclick="xep('ep07')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M8.5 1.5L10.5 3.5L4 10H2V8L8.5 1.5Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"></path></svg>Edit</button>
          <button class="xbtn xbtn--c" onclick="xcode('cp07','pv07')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M4 3L1 6L4 9M8 3L11 6L8 9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"></path></svg>Code</button>
          <button class="xbtn xbtn--p" id="hc07" onclick="xcopy('pv07','hc07')">Copy Code</button>
        </div>
      </div>
      <div class="bpb-ep" id="ep07">
        <div class="bpb-ep-rows">
          <div class="xf xf--w"><label>Main text (plain)</label><input id="f07-txt" value="Want to see this in your workflow?" oninput="u07()"></div>
          <div class="xf xf--w"><label>Highlighted text (span)</label><input id="f07-span" value="Get a personalised Botphonic demo in 20 minutes." oninput="u07()"></div>
          <div class="xf"><label>Button Text</label><input id="f07-bt" value="Book Demo" oninput="u07()"></div>
          <div class="xf xf--w"><label>Button URL</label><input id="f07-bl" value="https://calendly.com/contact-botphonic/product-discovery" oninput="u07()"></div>
        </div>
      </div>
      <div class="bpb-card__preview"><div id="pv07">
        <div class="cta-wrap" style="padding-top:0"><div class="cta-block"><div class="cta-minimal-nudge">
          <div class="mn-text"><span id="l07-txt">Want to see this in your workflow?</span> <span id="l07-span">Get a personalised Botphonic demo in 20 minutes.</span></div>
          <a id="l07-btn" href="https://calendly.com/contact-botphonic/product-discovery" class="bp-btn button bp-btn-primary bp-btn-sm">Book Demo <svg class="arrow" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"></path></svg></a>
        </div></div></div>
      </div></div>
      <div class="bpb-codepane" id="cp07"><div class="bpb-code-hdr"><span>HTML</span><button class="bpb-code-cp" id="pc07" onclick="xpre('pre07','pc07')">Copy</button></div><div class="bpb-code-body"><pre id="pre07"></pre></div></div>
    </div>

    <!---- CTA 08 - DEEP END ---->
    <div class="bpb-card" id="bpb-08">
      <div class="bpb-card__hdr">
        <div class="bpb-card__meta"><span class="bpb-card__idx">08</span><span class="bpb-card__name">Deep End - .cta-deep-end</span><span class="bpb-og">original class</span></div>
        <div class="bpb-card__acts">
          <button class="xbtn xbtn--e" onclick="xep('ep08')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M8.5 1.5L10.5 3.5L4 10H2V8L8.5 1.5Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"></path></svg>Edit</button>
          <button class="xbtn xbtn--c" onclick="xcode('cp08','pv08')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M4 3L1 6L4 9M8 3L11 6L8 9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"></path></svg>Code</button>
          <button class="xbtn xbtn--p" id="hc08" onclick="xcopy('pv08','hc08')">Copy Code</button>
        </div>
      </div>
      <div class="bpb-ep" id="ep08">
        <div class="bpb-ep-rows">
          <div class="xf"><label>Kicker</label><input id="f08-kick" value="Ready when you are" oninput="u08()"></div>
          <div class="xf xf--w"><label>Headline line 1</label><input id="f08-hl1" value="Stop Losing Leads to" oninput="u08()"></div>
          <div class="xf xf--w"><label>Headline line 2 (accent)</label><input id="f08-hl2" value="Unanswered Calls" oninput="u08()"></div>
          <div class="xf xf--f"><label>Subtext</label><textarea id="f08-sub" oninput="u08()">Botphonic's AI voice agents answer every call, qualify every lead, and schedule every appointment - around the clock, without adding headcount.</textarea></div>
          <div class="xdyn">
            <div class="xdyn__lbl">Buttons <button class="xadd" onclick="xAddBtn('btn08-rows','u08')">+ Add</button></div>
            <div id="btn08-rows">
              <div class="xdyn-row"><input value="Start Free - No Card Needed" oninput="u08()"><input class="url" value="https://app.botphonic.ai/register/" oninput="u08()"><input placeholder="Class" value="bp-btn button bp-btn-primary bp-btn-lg" oninput="u08()"><button class="xrm" onclick="xRm(this,'btn08-rows',u08)">✕</button></div>
              <div class="xdyn-row"><input value="Book a Live Demo" oninput="u08()"><input class="url" value="https://calendly.com/contact-botphonic/product-discovery" oninput="u08()"><input placeholder="Class" value="bp-btn button bp-btn-outline-white bp-btn-lg" oninput="u08()"><button class="xrm" onclick="xRm(this,'btn08-rows',u08)">✕</button></div>
            </div>
          </div>
          <div class="xdyn">
            <div class="xdyn__lbl">Trust Items <button class="xadd" onclick="xAddTick('trust08-rows','u08')">+ Add</button></div>
            <div id="trust08-rows">
              <div class="xdyn-row"><input value="SOC2 Certified" oninput="u08()"><button class="xrm" onclick="xRm(this,'trust08-rows',u08)">✕</button></div>
              <div class="xdyn-row"><input value="HIPAA Compliant" oninput="u08()"><button class="xrm" onclick="xRm(this,'trust08-rows',u08)">✕</button></div>
              <div class="xdyn-row"><input value="Live in 48 Hours" oninput="u08()"><button class="xrm" onclick="xRm(this,'trust08-rows',u08)">✕</button></div>
              <div class="xdyn-row"><input value="Cancel Anytime" oninput="u08()"><button class="xrm" onclick="xRm(this,'trust08-rows',u08)">✕</button></div>
            </div>
          </div>
        </div>
      </div>
      <div class="bpb-card__preview"><div id="pv08">
        <div class="cta-wrap" style="padding-top:0"><div class="cta-block"><div class="cta-deep-end">
          <div class="de-kicker"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg><span id="l08-kick">Ready when you are</span></div>
          <h3><span id="l08-hl1">Stop Losing Leads to</span><br><span id="l08-hl2">Unanswered Calls</span></h3>
          <p id="l08-sub">Botphonic's AI voice agents answer every call, qualify every lead, and schedule every appointment - around the clock, without adding headcount.</p>
          <div class="de-btns" id="l08-btns"></div>
          <div class="de-trust" id="l08-trust"></div>
        </div></div></div>
      </div></div>
      <div class="bpb-codepane" id="cp08"><div class="bpb-code-hdr"><span>HTML</span><button class="bpb-code-cp" id="pc08" onclick="xpre('pre08','pc08')">Copy</button></div><div class="bpb-code-body"><pre id="pre08"></pre></div></div>
    </div>

    <!---- CTA 09 - SLASH COMPARE ---->
    <div class="bpb-card" id="bpb-09">
      <div class="bpb-card__hdr">
        <div class="bpb-card__meta"><span class="bpb-card__idx">09</span><span class="bpb-card__name">Slash Compare - .cta-slash-compare</span><span class="bpb-og">original class</span></div>
        <div class="bpb-card__acts">
          <button class="xbtn xbtn--e" onclick="xep('ep09')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M8.5 1.5L10.5 3.5L4 10H2V8L8.5 1.5Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"></path></svg>Edit</button>
          <button class="xbtn xbtn--c" onclick="xcode('cp09','pv09')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M4 3L1 6L4 9M8 3L11 6L8 9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"></path></svg>Code</button>
          <button class="xbtn xbtn--p" id="hc09" onclick="xcopy('pv09','hc09')">Copy Code</button>
        </div>
      </div>
      <div class="bpb-ep" id="ep09">
        <div class="bpb-ep-rows">
          <div class="xf"><label>Header Label</label><input id="f09-hdr" value="Side-by-Side Comparison" oninput="u09()"></div>
          <div class="xf xf--w"><label>Competitor Name</label><input id="f09-them" value="CallHippo" oninput="u09()"></div>
          <div class="xf xf--w"><label>Your Brand Name</label><input id="f09-us" value="Botphonic" oninput="u09()"></div>
          <div class="xdyn">
            <div class="xdyn__lbl">Competitor Negatives <button class="xadd" onclick="xAddTick('them09-rows','u09')">+ Add</button></div>
            <div id="them09-rows">
              <div class="xdyn-row"><input value="Per-minute billing adds up fast" oninput="u09()"><button class="xrm" onclick="xRm(this,'them09-rows',u09)">✕</button></div>
              <div class="xdyn-row"><input value="Limited AI - mostly call routing" oninput="u09()"><button class="xrm" onclick="xRm(this,'them09-rows',u09)">✕</button></div>
              <div class="xdyn-row"><input value="No scheduling automation" oninput="u09()"><button class="xrm" onclick="xRm(this,'them09-rows',u09)">✕</button></div>
              <div class="xdyn-row"><input value="Needs human agents after-hours" oninput="u09()"><button class="xrm" onclick="xRm(this,'them09-rows',u09)">✕</button></div>
            </div>
          </div>
          <div class="xdyn">
            <div class="xdyn__lbl">Your Positives <button class="xadd" onclick="xAddTick('us09-rows','u09')">+ Add</button></div>
            <div id="us09-rows">
              <div class="xdyn-row"><input value="Flat pricing - no surprises" oninput="u09()"><button class="xrm" onclick="xRm(this,'us09-rows',u09)">✕</button></div>
              <div class="xdyn-row"><input value="Full AI voice conversations" oninput="u09()"><button class="xrm" onclick="xRm(this,'us09-rows',u09)">✕</button></div>
              <div class="xdyn-row"><input value="Books appointments automatically" oninput="u09()"><button class="xrm" onclick="xRm(this,'us09-rows',u09)">✕</button></div>
              <div class="xdyn-row"><input value="True 24/7 AI - no human needed" oninput="u09()"><button class="xrm" onclick="xRm(this,'us09-rows',u09)">✕</button></div>
            </div>
          </div>
          <div class="xf xf--f"><label>Footer text</label><input id="f09-foot" value="Make the smart switch - live in under 48 hours." oninput="u09()"></div>
          <div class="xf"><label>Button Text</label><input id="f09-bt" value="Try Botphonic Free" oninput="u09()"></div>
          <div class="xf xf--w"><label>Button URL</label><input id="f09-bl" value="https://app.botphonic.ai/register/" oninput="u09()"></div>
        </div>
      </div>
      <div class="bpb-card__preview"><div id="pv09">
        <div class="cta-wrap" style="padding-top:0"><div class="cta-block"><div class="cta-slash-compare">
          <div class="sc-header"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg><span id="l09-hdr">Side-by-Side Comparison</span></div>
          <div class="sc-grid">
            <div class="sc-col sc-them">
              <div class="col-label"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="icon-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg><span id="l09-them">CallHippo</span></div>
              <ul class="sc-list" id="l09-them-list"></ul>
            </div>
            <div class="sc-divider"><div class="vs-pill">VS</div></div>
            <div class="sc-col sc-us">
              <div class="col-label"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg><span id="l09-us">Botphonic</span></div>
              <ul class="sc-list" id="l09-us-list"></ul>
            </div>
          </div>
          <div class="sc-footer">
            <p id="l09-foot">Make the <strong>smart switch</strong> - live in under 48 hours.</p>
            <a id="l09-btn" href="https://app.botphonic.ai/register/" class="bp-btn button bp-btn-primary">Try Botphonic Free <svg class="arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"></path></svg></a>
          </div>
        </div></div></div>
      </div></div>
      <div class="bpb-codepane" id="cp09"><div class="bpb-code-hdr"><span>HTML</span><button class="bpb-code-cp" id="pc09" onclick="xpre('pre09','pc09')">Copy</button></div><div class="bpb-code-body"><pre id="pre09"></pre></div></div>
    </div>

    <!---- CTA 10 - BOOKING WIDGET ---->
    <div class="bpb-card" id="bpb-10">
      <div class="bpb-card__hdr">
        <div class="bpb-card__meta"><span class="bpb-card__idx">10</span><span class="bpb-card__name">Booking Widget - .cta-booking-widget</span><span class="bpb-og">original class</span></div>
        <div class="bpb-card__acts">
          <button class="xbtn xbtn--e" onclick="xep('ep10')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M8.5 1.5L10.5 3.5L4 10H2V8L8.5 1.5Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"></path></svg>Edit</button>
          <button class="xbtn xbtn--c" onclick="xcode('cp10','pv10')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M4 3L1 6L4 9M8 3L11 6L8 9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"></path></svg>Code</button>
          <button class="xbtn xbtn--p" id="hc10" onclick="xcopy('pv10','hc10')">Copy Code</button>
        </div>
      </div>
      <div class="bpb-ep" id="ep10">
        <div class="bpb-ep-rows">
          <div class="xf xf--w"><label>Headline</label><input id="f10-hl" value="Book a Free 20-Min Product Walkthrough" oninput="u10()"></div>
          <div class="xf xf--f"><label>Subtext</label><textarea id="f10-sub" oninput="u10()">See Botphonic in action - live demo tailored to your industry.</textarea></div>
          <div class="xf xf--f"><label>Slot label</label><input id="f10-slbl" value="Pick a time · opens Calendly instantly" oninput="u10()"></div>
          <div class="xdyn">
            <div class="xdyn__lbl">Time Slots (Label · Calendly DateTime) <button class="xadd" onclick="xAddSlot('slot10-rows','u10')">+ Add</button></div>
            <div id="slot10-rows">
              <div class="xdyn-row"><input placeholder="Thu, Mar 26 · 10:00 AM" value="Thu, Mar 26 · 10:00 AM" oninput="u10()"><input class="url" placeholder="2026-03-26T10:00:00+05:30" value="2026-03-26T10:00:00+05:30" oninput="u10()"><button class="xrm" onclick="xRm(this,'slot10-rows',u10)">✕</button></div>
              <div class="xdyn-row"><input value="Thu, Mar 26 · 2:00 PM" oninput="u10()"><input class="url" value="2026-03-26T14:00:00+05:30" oninput="u10()"><button class="xrm" onclick="xRm(this,'slot10-rows',u10)">✕</button></div>
              <div class="xdyn-row"><input value="Fri, Mar 27 · 11:00 AM" oninput="u10()"><input class="url" value="2026-03-27T11:00:00+05:30" oninput="u10()"><button class="xrm" onclick="xRm(this,'slot10-rows',u10)">✕</button></div>
              <div class="xdyn-row"><input value="Sat, Mar 28 · 3:00 PM" oninput="u10()"><input class="url" value="2026-03-28T15:00:00+05:30" oninput="u10()"><button class="xrm" onclick="xRm(this,'slot10-rows',u10)">✕</button></div>
            </div>
          </div>
          <div class="xf xf--f"><label>Confirmation note</label><input id="f10-note" value="Confirmed instantly. Calendar invite sent automatically after booking." oninput="u10()"></div>
          <div class="xf"><label>CTA Button Text</label><input id="f10-bt" value="Confirm Booking" oninput="u10()"></div>
        </div>
      </div>
      <div class="bpb-card__preview"><div id="pv10">
        <div class="cta-wrap" style="padding-top:0"><div class="cta-block"><div class="cta-booking-widget">
          <div class="bw-header">
            <div class="bw-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></div>
            <div><h4 id="l10-hl">Book a Free 20-Min Product Walkthrough</h4><p id="l10-sub">See Botphonic in action - live demo tailored to your industry.</p></div>
          </div>
          <div class="bw-slots">
            <div class="slot-label" id="l10-slbl">Pick a time · opens Calendly instantly</div>
            <div class="bw-pills" id="l10-slots"></div>
          </div>
          <div class="bw-bottom">
            <div class="bw-note" id="l10-note"><strong>Confirmed instantly.</strong> Calendar invite sent automatically after booking.</div>
            <a href="#" onclick="typeof openCalendly==='function'&&openCalendly();return false;" class="bp-btn button bp-btn-primary" id="l10-btn">Confirm Booking <svg class="arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"></path></svg></a>
          </div>
        </div></div></div>
      </div></div>
      <div class="bpb-codepane" id="cp10"><div class="bpb-code-hdr"><span>HTML</span><button class="bpb-code-cp" id="pc10" onclick="xpre('pre10','pc10')">Copy</button></div><div class="bpb-code-body"><pre id="pre10"></pre></div></div>
    </div>

    <!---- CTA 11 - TRUST RIBBON ---->
    <div class="bpb-card" id="bpb-11">
      <div class="bpb-card__hdr">
        <div class="bpb-card__meta"><span class="bpb-card__idx">11</span><span class="bpb-card__name">Trust Ribbon - .cta-trust-ribbon</span><span class="bpb-og">original class</span></div>
        <div class="bpb-card__acts">
          <button class="xbtn xbtn--e" onclick="xep('ep11')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M8.5 1.5L10.5 3.5L4 10H2V8L8.5 1.5Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"></path></svg>Edit</button>
          <button class="xbtn xbtn--c" onclick="xcode('cp11','pv11')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M4 3L1 6L4 9M8 3L11 6L8 9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"></path></svg>Code</button>
          <button class="xbtn xbtn--p" id="hc11" onclick="xcopy('pv11','hc11')">Copy Code</button>
        </div>
      </div>
      <div class="bpb-ep" id="ep11">
        <div class="bpb-ep-rows">
          <div class="xdyn">
            <div class="xdyn__lbl">Trust Badges <button class="xadd" onclick="xAddTick('trust11-rows','u11')">+ Add</button></div>
            <div id="trust11-rows">
              <div class="xdyn-row"><input value="SOC2 Certified" oninput="u11()"><button class="xrm" onclick="xRm(this,'trust11-rows',u11)">✕</button></div>
              <div class="xdyn-row"><input value="HIPAA Compliant" oninput="u11()"><button class="xrm" onclick="xRm(this,'trust11-rows',u11)">✕</button></div>
              <div class="xdyn-row"><input value="GDPR Ready" oninput="u11()"><button class="xrm" onclick="xRm(this,'trust11-rows',u11)">✕</button></div>
              <div class="xdyn-row"><input value="99.9% Uptime SLA" oninput="u11()"><button class="xrm" onclick="xRm(this,'trust11-rows',u11)">✕</button></div>
            </div>
          </div>
          <div class="xf"><label>Button Text</label><input id="f11-bt" value="Start Free Trial" oninput="u11()"></div>
          <div class="xf xf--w"><label>Button URL</label><input id="f11-bl" value="https://app.botphonic.ai/register/" oninput="u11()"></div>
        </div>
      </div>
      <div class="bpb-card__preview"><div id="pv11">
        <div class="cta-wrap" style="padding-top:0"><div class="cta-block"><div class="cta-trust-ribbon">
          <div class="tr-trust" id="l11-badges"></div>
          <a id="l11-btn" href="https://app.botphonic.ai/register/" class="bp-btn button bp-btn-primary bp-btn-sm">Start Free Trial <svg class="arrow" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"></path></svg></a>
        </div></div></div>
      </div></div>
      <div class="bpb-codepane" id="cp11"><div class="bpb-code-hdr"><span>HTML</span><button class="bpb-code-cp" id="pc11" onclick="xpre('pre11','pc11')">Copy</button></div><div class="bpb-code-body"><pre id="pre11"></pre></div></div>
    </div>

    <div class="bpb-sec-div"><span>Blog Components</span></div>

    <!-- BLOG: LEARN MORE FULL -->
    <div class="bpb-card" id="bpb-lmf">
      <div class="bpb-card__hdr">
        <div class="bpb-card__meta"><span class="bpb-card__idx">Blog</span><span class="bpb-card__name">Learn More - Full Text</span><span class="bpb-og">original class</span></div>
        <div class="bpb-card__acts">
          <button class="xbtn xbtn--e" onclick="xep('ep-lmf')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M8.5 1.5L10.5 3.5L4 10H2V8L8.5 1.5Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"></path></svg>Edit</button>
          <button class="xbtn xbtn--c" onclick="xcode('cp-lmf','pv-lmf')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M4 3L1 6L4 9M8 3L11 6L8 9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"></path></svg>Code</button>
          <button class="xbtn xbtn--p" id="hc-lmf" onclick="xcopy('pv-lmf','hc-lmf')">Copy Code</button>
        </div>
      </div>
      <div class="bpb-ep" id="ep-lmf">
        <div class="bpb-ep-rows">
          <div class="xf xf--f"><label>Body text (before link)</label><textarea id="flmf-body" oninput="ulmf()">AI answering services reduce missed calls significantly, but choosing the right platform matters and</textarea></div>
          <div class="xf xf--w"><label>Link Text</label><input id="flmf-lt" value="Why Realtors Need AI in 2025" oninput="ulmf()"></div>
          <div class="xf xf--f"><label>Link URL</label><input id="flmf-ll" value="https://botphonic.ai/why-realtors-need-ai-in-2025/" oninput="ulmf()"></div>
          <div class="xf xf--f"><label>Text after link</label><input id="flmf-after" value="covers exactly what to look for before you commit." oninput="ulmf()"></div>
        </div>
      </div>
      <div class="bpb-card__preview"><div id="pv-lmf">
        <div class="learn-more-box-alt"><p><b>Learn more:</b> <span id="llmf-body">AI answering services reduce missed calls significantly, but choosing the right platform matters and</span> <a id="llmf-link" href="https://botphonic.ai/why-realtors-need-ai-in-2025/" target="_blank" rel="noopener">Why Realtors Need AI in 2025</a> <span id="llmf-after">covers exactly what to look for before you commit.</span></p></div>
      </div></div>
      <div class="bpb-codepane" id="cp-lmf"><div class="bpb-code-hdr"><span>HTML - paste into blog post</span><button class="bpb-code-cp" id="pc-lmf" onclick="xpre('pre-lmf','pc-lmf')">Copy</button></div><div class="bpb-code-body"><pre id="pre-lmf"></pre></div></div>
    </div>

    <!-- BLOG: LEARN MORE LINK ONLY -->
    <div class="bpb-card" id="bpb-lml">
      <div class="bpb-card__hdr">
        <div class="bpb-card__meta"><span class="bpb-card__idx">Blog</span><span class="bpb-card__name">Learn More - Link Only</span><span class="bpb-og">original class</span></div>
        <div class="bpb-card__acts">
          <button class="xbtn xbtn--e" onclick="xep('ep-lml')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M8.5 1.5L10.5 3.5L4 10H2V8L8.5 1.5Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"></path></svg>Edit</button>
          <button class="xbtn xbtn--c" onclick="xcode('cp-lml','pv-lml')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M4 3L1 6L4 9M8 3L11 6L8 9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"></path></svg>Code</button>
          <button class="xbtn xbtn--p" id="hc-lml" onclick="xcopy('pv-lml','hc-lml')">Copy Code</button>
        </div>
      </div>
      <div class="bpb-ep" id="ep-lml">
        <div class="bpb-ep-rows">
          <div class="xf xf--w"><label>Link Text</label><input id="flml-lt" value="Why do realtors need AI?" oninput="ulml()"></div>
          <div class="xf xf--f"><label>Link URL</label><input id="flml-ll" value="https://botphonic.ai/why-realtors-need-ai-in-2025/" oninput="ulml()"></div>
        </div>
      </div>
      <div class="bpb-card__preview"><div id="pv-lml">
        <div class="learn-more-box-alt"><p><b>Learn more:</b> <a id="llml-link" href="https://botphonic.ai/why-realtors-need-ai-in-2025/" target="_blank" rel="noopener">Why do realtors need AI?</a></p></div>
      </div></div>
      <div class="bpb-codepane" id="cp-lml"><div class="bpb-code-hdr"><span>HTML - paste into blog post</span><button class="bpb-code-cp" id="pc-lml" onclick="xpre('pre-lml','pc-lml')">Copy</button></div><div class="bpb-code-body"><pre id="pre-lml"></pre></div></div>
    </div>

    <!-- BLOG: NOTE BOX -->
    <div class="bpb-card" id="bpb-note">
      <div class="bpb-card__hdr">
        <div class="bpb-card__meta"><span class="bpb-card__idx">Blog</span><span class="bpb-card__name">Note Box</span><span class="bpb-og">original class</span></div>
        <div class="bpb-card__acts">
          <button class="xbtn xbtn--e" onclick="xep('ep-note')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M8.5 1.5L10.5 3.5L4 10H2V8L8.5 1.5Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"></path></svg>Edit</button>
          <button class="xbtn xbtn--c" onclick="xcode('cp-note','pv-note')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M4 3L1 6L4 9M8 3L11 6L8 9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"></path></svg>Code</button>
          <button class="xbtn xbtn--p" id="hc-note" onclick="xcopy('pv-note','hc-note')">Copy Code</button>
        </div>
      </div>
      <div class="bpb-ep" id="ep-note">
        <div class="bpb-ep-rows">
          <div class="xf"><label>Label</label><input id="fnote-lbl" value="NOTE" oninput="unote()"></div>
          <div class="xf xf--f"><label>Content</label><textarea id="fnote-content" oninput="unote()">Ensure your AI voice bot supports CRM integration, multilingual responses, and SOC2-compliant data handling before going live.</textarea></div>
          <div class="xf xf--f"><label>Icon Image URL</label><input id="fnote-icon" value="https://botphonic.ai/wp-content/uploads/2025/07/Notes-Icon.svg" oninput="unote()"></div>
        </div>
      </div>
      <div class="bpb-card__preview"><div id="pv-note">
        <div class="remember-box-light"><div class="remember-icon"><img id="lnote-icon" src="https://botphonic.ai/wp-content/uploads/2025/07/Notes-Icon.svg" alt="Note Icon" onerror="this.style.display='none'"><span id="lnote-lbl">NOTE</span></div><div class="remember-content" id="lnote-content">Ensure your AI voice bot supports CRM integration, multilingual responses, and SOC2-compliant data handling before going live.</div></div>
      </div></div>
      <div class="bpb-codepane" id="cp-note"><div class="bpb-code-hdr"><span>HTML - paste into blog post</span><button class="bpb-code-cp" id="pc-note" onclick="xpre('pre-note','pc-note')">Copy</button></div><div class="bpb-code-body"><pre id="pre-note"></pre></div></div>
    </div>

    <!-- BLOG: PRO TIP BOX -->
    <div class="bpb-card" id="bpb-tip">
      <div class="bpb-card__hdr">
        <div class="bpb-card__meta"><span class="bpb-card__idx">Blog</span><span class="bpb-card__name">Pro Tip Box</span><span class="bpb-og">original class</span></div>
        <div class="bpb-card__acts">
          <button class="xbtn xbtn--e" onclick="xep('ep-tip')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M8.5 1.5L10.5 3.5L4 10H2V8L8.5 1.5Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"></path></svg>Edit</button>
          <button class="xbtn xbtn--c" onclick="xcode('cp-tip','pv-tip')"><svg width="10" height="10" viewBox="0 0 12 12" fill="none" style="margin-right:3px"><path d="M4 3L1 6L4 9M8 3L11 6L8 9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"></path></svg>Code</button>
          <button class="xbtn xbtn--p" id="hc-tip" onclick="xcopy('pv-tip','hc-tip')">Copy Code</button>
        </div>
      </div>
      <div class="bpb-ep" id="ep-tip">
        <div class="bpb-ep-rows">
          <div class="xf"><label>Label</label><input id="ftip-lbl" value="PRO TIP" oninput="utip()"></div>
          <div class="xf xf--f"><label>Content</label><textarea id="ftip-content" oninput="utip()">"Choose an AI voice bot that not only handles today's call volume but scales with your business growth and integrates natively with your existing CRM."</textarea></div>
          <div class="xf xf--f"><label>Icon Image URL</label><input id="ftip-icon" value="https://botphonic.ai/wp-content/uploads/2025/07/Pro-Tips.svg" oninput="utip()"></div>
        </div>
      </div>
      <div class="bpb-card__preview"><div id="pv-tip">
        <div class="blog-tip-box"><div class="tip-icon"><img id="ltip-icon" src="https://botphonic.ai/wp-content/uploads/2025/07/Pro-Tips.svg" alt="Pro Tips" onerror="this.style.display='none'"><span id="ltip-lbl">PRO TIP</span></div><div class="tip-content" id="ltip-content">"Choose an AI voice bot that not only handles today's call volume but scales with your business growth and integrates natively with your existing CRM."</div></div>
      </div></div>
      <div class="bpb-codepane" id="cp-tip"><div class="bpb-code-hdr"><span>HTML - paste into blog post</span><button class="bpb-code-cp" id="pc-tip" onclick="xpre('pre-tip','pc-tip')">Copy</button></div><div class="bpb-code-body"><pre id="pre-tip"></pre></div></div>
    </div>

  </main>
</div>
<div class="bpb-toast" id="bpb-toast">Copied to clipboard!</div>

<script>
const xg=id=>document.getElementById(id);
const xv=id=>{const e=xg(id);return e?e.value:''};

/* SVGs used in dynamic output */
const SVG_ARR=`<svg class="arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"></path></svg>`;
const SVG_CHK=`<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="icon-check"><polyline points="20 6 9 17 4 12"></polyline></svg>`;
const SVG_X=`<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="icon-x" style="flex-shrink:0;margin-top:2px;"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>`;
const SVG_BOLT=`<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>`;

/* ── Builder helpers ── */
function xep(id){xg(id).classList.toggle('open')}
function xcode(cpId,pvId){const p=xg(cpId);p.classList.toggle('open');if(p.classList.contains('open'))xrender(cpId,pvId)}
function xrender(cpId,pvId){const preId='pre'+cpId.replace('cp','');const pre=xg(preId),prev=xg(pvId);if(!pre||!prev)return;pre.textContent=xpretty(prev.innerHTML.trim())}
function xpretty(html){
  /* strip builder-only live ids */
  html=html.replace(/\s+id="l[0-9a-z-]+"/g,'');
  const T='  ';let d=0;
  return html.replace(/></g,'>\n<').split('\n').map(line=>{
    line=line.trim();if(!line)return'';
    const isC=/^<\//.test(line),isS=/\/>$/.test(line),isI=/^<(a|span|strong|b|em|i|small|svg|path|circle|line|polyline|polygon|rect)\b/.test(line);
    if(isC)d=Math.max(0,d-1);
    const out=T.repeat(d)+line;
    if(!isC&&!isS&&!isI&&/^<[^!]/.test(line))d++;
    return out;
  }).filter(Boolean).join('\n');
}
function xcopy(pvId,btnId){const p=xg(pvId),b=xg(btnId);if(!p||!b)return;navigator.clipboard.writeText(xpretty(p.innerHTML.trim())).then(()=>{b.textContent='Copied!';b.classList.add('ok');xtoast();setTimeout(()=>{b.textContent='Copy Code';b.classList.remove('ok')},2200)})}
function xpre(preId,btnId){const p=xg(preId),b=xg(btnId);if(!p||!b)return;navigator.clipboard.writeText(p.textContent).then(()=>{b.textContent='Copied';b.classList.add('ok');xtoast();setTimeout(()=>{b.textContent='Copy';b.classList.remove('ok')},2200)})}
function xtoast(){const t=xg('bpb-toast');t.classList.add('show');setTimeout(()=>t.classList.remove('show'),2400)}
function xrc(cpId,pvId){if(xg(cpId).classList.contains('open'))xrender(cpId,pvId)}

/* ── Dynamic row adders ── */
function xRm(btn,wrId,fn){btn.parentElement.remove();fn()}
function xAddBtn(wrId,fnName){const r=document.createElement('div');r.className='xdyn-row';r.innerHTML=`<input placeholder="Button text" oninput="${fnName}()"><input class="url" placeholder="https://..." oninput="${fnName}()"><input placeholder="Class" value="bp-btn button bp-btn-primary" oninput="${fnName}()"><button class="xrm" onclick="xRm(this,'${wrId}',${fnName})">✕</button>`;xg(wrId).appendChild(r);eval(fnName+'()')}
function xAddStat(wrId,fnName){const r=document.createElement('div');r.className='xdyn-row';r.innerHTML=`<input placeholder="2.5×" oninput="${fnName}()" style="max-width:70px"><input placeholder="Label" oninput="${fnName}()"><button class="xrm" onclick="xRm(this,'${wrId}',${fnName})">✕</button>`;xg(wrId).appendChild(r);eval(fnName+'()')}
function xAddTick(wrId,fnName){const r=document.createElement('div');r.className='xdyn-row';r.innerHTML=`<input placeholder="Item text" oninput="${fnName}()"><button class="xrm" onclick="xRm(this,'${wrId}',${fnName})">✕</button>`;xg(wrId).appendChild(r);eval(fnName+'()')}
function xAddFeat(wrId,fnName){const r=document.createElement('div');r.className='xdyn-row';r.innerHTML=`<input placeholder="Feature title" oninput="${fnName}()"><input placeholder="Description" oninput="${fnName}()"><button class="xrm" onclick="xRm(this,'${wrId}',${fnName})">✕</button>`;xg(wrId).appendChild(r);eval(fnName+'()')}
function xAddSlot(wrId,fnName){const r=document.createElement('div');r.className='xdyn-row';r.innerHTML=`<input placeholder="Thu, Mar 26 · 10:00 AM" oninput="${fnName}()"><input class="url" placeholder="2026-03-26T10:00:00+05:30" oninput="${fnName}()"><button class="xrm" onclick="xRm(this,'${wrId}',${fnName})">✕</button>`;xg(wrId).appendChild(r);eval(fnName+'()')}

/* helper: render buttons from a rows container */
function xRenderBtns(rowsId,wrapId,withArrow){
  const w=xg(wrapId);w.innerHTML='';
  xg(rowsId).querySelectorAll('.xdyn-row').forEach(row=>{
    const ins=row.querySelectorAll('input');
    const txt=(ins[0]&&ins[0].value.trim()),url=(ins[1]&&ins[1].value.trim()),cls=(ins[2]&&ins[2].value.trim())||'bp-btn button bp-btn-primary';
    if(!txt)return;
    const a=document.createElement('a');a.className=cls;a.textContent=txt;a.href=url||'#';
    if(withArrow)a.innerHTML=txt+' '+SVG_ARR;
    w.appendChild(a);
  });
}

/* ── 01 Pulse Banner ── */
function u01(){
  xg('l01-live').textContent=xv('f01-live');
  xg('l01-hl').textContent=xv('f01-hl');
  xg('l01-sub').textContent=xv('f01-sub');
  xRenderBtns('btn01-rows','l01-btns',true);
  xrc('cp01','pv01');
}

/* ── 02 Metric Punch ── */
function u02(){
  xg('l02-hl').textContent=xv('f02-hl');
  xg('l02-sub').textContent=xv('f02-sub');
  xg('l02-trust').textContent=xv('f02-trust');
  xg('l02-btn').textContent=xv('f02-bt')+' ';xg('l02-btn').href=xv('f02-bl');
  xg('l02-btn').innerHTML=xv('f02-bt')+' '+SVG_ARR;
  const sw=xg('l02-stats');sw.innerHTML='';
  xg('stat02-rows').querySelectorAll('.xdyn-row').forEach(row=>{
    const ins=row.querySelectorAll('input');const num=ins[0]&&ins[0].value.trim(),lbl=ins[1]&&ins[1].value.trim();if(!num)return;
    const d=document.createElement('div');d.className='mp-stat';
    d.innerHTML=`<span class="num" style="font-size:28px;font-weight:800;color:var(--accent);display:block;line-height:1;letter-spacing:-.02em;">${num}</span><span class="lbl" style="font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;">${lbl}</span>`;
    sw.appendChild(d);
  });
  xrc('cp02','pv02');
}

/* ── 03 Spotlight Card ── */
function u03(){
  xg('l03-eye').textContent=xv('f03-eye');
  xg('l03-hl').innerHTML=xv('f03-hl');
  xg('l03-sub').textContent=xv('f03-sub');
  const b1=xg('l03-b1');b1.innerHTML=xv('f03-b1t')+' '+SVG_ARR;b1.href=xv('f03-b1l');
  const b2=xg('l03-b2');b2.textContent=xv('f03-b2t');b2.href=xv('f03-b2l');
  const tw=xg('l03-ticks');tw.innerHTML='';
  xg('tick03-rows').querySelectorAll('.xdyn-row').forEach(row=>{
    const txt=row.querySelector('input').value.trim();if(!txt)return;
    const d=document.createElement('div');d.className='tick';d.innerHTML=SVG_CHK+txt;tw.appendChild(d);
  });
  xrc('cp03','pv03');
}

/* ── 04 Switch Strip ── */
function u04(){
  xg('l04-badge').textContent=xv('f04-badge');
  xg('l04-hl').textContent=xv('f04-hl');
  xg('l04-sub').textContent=xv('f04-sub');
  const b1=xg('l04-b1');b1.innerHTML=xv('f04-b1t')+' '+SVG_ARR;b1.href=xv('f04-b1l');
  const b2=xg('l04-b2');b2.textContent=xv('f04-b2t');b2.href=xv('f04-b2l');
  xg('l04-note').textContent=xv('f04-note');
  xrc('cp04','pv04');
}

/* ── 05 Value Grid ── */
function u05(){
  xg('l05-hl').textContent=xv('f05-hl');
  xg('l05-sub').textContent=xv('f05-sub');
  /* features */
  const fw=xg('l05-feats');fw.innerHTML='';
  const icons=['<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2" stroke-linecap="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 01.22 1.19 2 2 0 012.22 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"></path></svg>','<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#06B6D4" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>','<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#FF7E55" stroke-width="2" stroke-linecap="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>'];
  const ibgs=['rgba(37,99,235,.08)','rgba(6,182,212,.1)','rgba(255,126,85,.1)'];
  xg('feat05-rows').querySelectorAll('.xdyn-row').forEach((row,i)=>{
    const ins=row.querySelectorAll('input');const ttl=ins[0]&&ins[0].value.trim(),desc=ins[1]&&ins[1].value.trim();if(!ttl)return;
    const ic=icons[i%icons.length],ibg=ibgs[i%ibgs.length];
    const d=document.createElement('div');d.className='vg-feature';
    d.innerHTML=`<div class="icon-wrap" style="background:${ibg};">${ic}</div><strong>${ttl}</strong><span>${desc}</span>`;
    fw.appendChild(d);
  });
  /* trust */
  const tw=xg('l05-trust');tw.innerHTML='';
  xg('trust05-rows').querySelectorAll('.xdyn-row').forEach(row=>{
    const txt=row.querySelector('input').value.trim();if(!txt)return;
    const d=document.createElement('div');d.className='trust-item';d.innerHTML=SVG_CHK+txt;tw.appendChild(d);
  });
  xRenderBtns('btn05-rows','l05-btns',false);
  xrc('cp05','pv05');
}

/* ── 06 Orange Ignite ── */
function u06(){
  xg('l06-tag').textContent=xv('f06-tag');
  xg('l06-hl').textContent=xv('f06-hl');
  xg('l06-sub').textContent=xv('f06-sub');
  xg('l06-note').textContent=xv('f06-note');
  const btn=xg('l06-btn');btn.innerHTML=xv('f06-bt')+' '+SVG_ARR;btn.href=xv('f06-bl');
  xrc('cp06','pv06');
}

/* ── 07 Minimal Nudge ── */
function u07(){
  xg('l07-txt').textContent=xv('f07-txt');
  xg('l07-span').textContent=xv('f07-span');
  const btn=xg('l07-btn');btn.innerHTML=xv('f07-bt')+' '+SVG_ARR;btn.href=xv('f07-bl');
  xrc('cp07','pv07');
}

/* ── 08 Deep End ── */
function u08(){
  xg('l08-kick').textContent=xv('f08-kick');
  xg('l08-hl1').textContent=xv('f08-hl1');
  xg('l08-hl2').textContent=xv('f08-hl2');
  xg('l08-sub').textContent=xv('f08-sub');
  xRenderBtns('btn08-rows','l08-btns',true);
  const tw=xg('l08-trust');tw.innerHTML='';
  xg('trust08-rows').querySelectorAll('.xdyn-row').forEach(row=>{
    const txt=row.querySelector('input').value.trim();if(!txt)return;
    const d=document.createElement('div');d.className='t-item';
    d.innerHTML=`<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>${txt}`;
    tw.appendChild(d);
  });
  xrc('cp08','pv08');
}

/* ── 09 Slash Compare ── */
function u09(){
  xg('l09-hdr').textContent=xv('f09-hdr');
  xg('l09-them').textContent=xv('f09-them');
  xg('l09-us').textContent=xv('f09-us');
  /* them list */
  const tl=xg('l09-them-list');tl.innerHTML='';
  xg('them09-rows').querySelectorAll('.xdyn-row').forEach(row=>{
    const txt=row.querySelector('input').value.trim();if(!txt)return;
    const li=document.createElement('li');li.innerHTML=SVG_X+txt;tl.appendChild(li);
  });
  /* us list */
  const ul=xg('l09-us-list');ul.innerHTML='';
  xg('us09-rows').querySelectorAll('.xdyn-row').forEach(row=>{
    const txt=row.querySelector('input').value.trim();if(!txt)return;
    const li=document.createElement('li');li.innerHTML=SVG_CHK+txt;ul.appendChild(li);
  });
  xg('l09-foot').innerHTML='Make the <strong>'+xv('f09-foot').replace('Make the smart switch - ','')+'</strong>';
  xg('l09-foot').textContent=xv('f09-foot');
  const btn=xg('l09-btn');btn.innerHTML=xv('f09-bt')+' '+SVG_ARR;btn.href=xv('f09-bl');
  xrc('cp09','pv09');
}

/* ── 10 Booking Widget ── */
function u10(){
  xg('l10-hl').textContent=xv('f10-hl');
  xg('l10-sub').textContent=xv('f10-sub');
  xg('l10-slbl').textContent=xv('f10-slbl');
  xg('l10-btn').innerHTML=xv('f10-bt')+' '+SVG_ARR;
  /* note */
  const noteEl=xg('l10-note');const noteVal=xv('f10-note');
  const boldEnd=noteVal.indexOf('.');
  if(boldEnd>0){noteEl.innerHTML='<strong>'+noteVal.substring(0,boldEnd+1)+'</strong>'+noteVal.substring(boldEnd+1);}
  else{noteEl.textContent=noteVal;}
  /* slots */
  const sw=xg('l10-slots');sw.innerHTML='';
  xg('slot10-rows').querySelectorAll('.xdyn-row').forEach((row,i)=>{
    const ins=row.querySelectorAll('input');const lbl=ins[0]&&ins[0].value.trim(),dt=ins[1]&&ins[1].value.trim();if(!lbl)return;
    const d=document.createElement('div');d.className='bw-pill'+(i===0?' active':'');
    d.setAttribute('onclick',`openCalendly('${dt}')`);d.textContent=lbl;sw.appendChild(d);
  });
  /* more times pill */
  const more=document.createElement('div');more.className='bw-pill';more.setAttribute('onclick','openCalendly()');more.style.cssText='background:rgba(37,99,235,.04);border-style:dashed;';more.textContent='+ More times →';sw.appendChild(more);
  xrc('cp10','pv10');
}

/* ── 11 Trust Ribbon ── */
function u11(){
  const bw=xg('l11-badges');bw.innerHTML='';
  const trustSVGs=[
    `<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>`,
    `<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>`,
    `<circle cx="12" cy="12" r="10"></circle><polyline points="20 6 9 17 4 12"></polyline>`,
    `<circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path>`
  ];
  let i=0;
  xg('trust11-rows').querySelectorAll('.xdyn-row').forEach(row=>{
    const txt=row.querySelector('input').value.trim();if(!txt)return;
    const svg=trustSVGs[i%trustSVGs.length];
    const d=document.createElement('div');d.className='tr-badge';
    d.innerHTML=`<div class="badge-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">${svg}</svg></div>${txt}`;
    if(i>0){const div=document.createElement('div');div.className='tr-divider';bw.appendChild(div);}
    bw.appendChild(d);i++;
  });
  const btn=xg('l11-btn');btn.innerHTML=xv('f11-bt')+' '+SVG_ARR;btn.href=xv('f11-bl');
  xrc('cp11','pv11');
}

/* ── Blog: Learn More Full ── */
function ulmf(){
  xg('llmf-body').textContent=xv('flmf-body');
  xg('llmf-link').textContent=xv('flmf-lt');xg('llmf-link').href=xv('flmf-ll');
  xg('llmf-after').textContent=' '+xv('flmf-after');
  xrc('cp-lmf','pv-lmf');
}

/* ── Blog: Learn More Link ── */
function ulml(){
  xg('llml-link').textContent=xv('flml-lt');xg('llml-link').href=xv('flml-ll');
  xrc('cp-lml','pv-lml');
}

/* ── Blog: Note ── */
function unote(){
  xg('lnote-lbl').textContent=xv('fnote-lbl');
  xg('lnote-content').textContent=xv('fnote-content');
  xg('lnote-icon').src=xv('fnote-icon');
  xrc('cp-note','pv-note');
}

/* ── Blog: Pro Tip ── */
function utip(){
  xg('ltip-lbl').textContent=xv('ftip-lbl');
  xg('ltip-content').textContent=xv('ftip-content');
  xg('ltip-icon').src=xv('ftip-icon');
  xrc('cp-tip','pv-tip');
}

/* scroll spy */
const bpbObs=new IntersectionObserver(entries=>{
  entries.forEach(e=>{
    if(e.isIntersecting){const id=e.target.id;document.querySelectorAll('.bpb-sb-item').forEach(el=>el.classList.toggle('active',el.getAttribute('href')==='#'+id));}
  });
},{threshold:0.35});
['bpb-01','bpb-02','bpb-03','bpb-04','bpb-05','bpb-06','bpb-07','bpb-08','bpb-09','bpb-10','bpb-11','bpb-lmf','bpb-lml','bpb-note','bpb-tip'].forEach(id=>{const el=document.getElementById(id);if(el)bpbObs.observe(el);});

/* init */
window.addEventListener('DOMContentLoaded',()=>{u01();u02();u03();u04();u05();u06();u07();u08();u09();u10();u11();});
</script>
<?php get_footer();?>