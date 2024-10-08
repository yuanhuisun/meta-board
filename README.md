<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>


# 功能需求设计书 (FRS)

## 1. 引言
本文档旨在详细描述MetaBoard网站的功能需求，以指导开发团队构建一个基于Laravel框架的IT项目和服务管理平台，并集成生成式AI Copilot以提升用户体验和操作效率。

## 2. 功能需求

### 2.1 用户认证模块
- **用例1: 用户注册**
  - **描述**: 用户填写注册表单，提供必要的信息如用户名、邮箱和密码，并通过邮箱验证激活账户。
  - **正常流程**:
    1. 用户访问注册页面。
    2. 用户填写所需信息并提交表单。
    3. 系统发送激活邮件到用户提供的邮箱地址。
    4. 用户点击邮件中的激活链接。
    5. 系统验证链接有效性并激活用户账户。
    6. 用户可以使用自己的凭据登录系统。
  - **异常流程**:
    - 用户提交表单时信息不完整或格式不正确，系统提示错误并要求重新填写。
    - 用户未收到激活邮件，可能是因为邮箱地址错误或邮件被过滤到垃圾箱。
    - 激活链接过期或无效，用户需要请求重新发送激活邮件。

### 2.2 用户管理模块
- **用例1: 管理用户信息**
  - **描述**: 管理员可以查看、编辑或删除用户账户信息。
  - **正常流程**:
    1. 管理员登录系统并访问用户管理界面。
    2. 管理员选择用户并进行查看、编辑或删除操作。
    3. 系统根据管理员的操作更新或删除用户信息。
  - **异常流程**:
    - 管理员尝试删除自己的账户，系统阻止并提示错误。
    - 管理员输入的编辑信息不完整或不符合要求，系统提示错误并要求重新输入。

- **用例2: 分配角色和权限**
  - **描述**: 管理员可以为用户分配不同的角色和权限，控制对系统功能的访问。
  - **正常流程**:
    1. 管理员登录系统并访问权限管理界面。
    2. 管理员选择用户并分配或修改角色和权限。
    3. 系统更新用户的角色和权限信息。
    4. 用户的系统访问权限根据新的角色和权限更新。
  - **异常流程**:
    - 管理员尝试修改自己的权限，系统阻止并提示错误。
    - 管理员尝试分配不存在的角色或权限，系统提示错误并要求重新输入。

### 2.3 项目管理模块
- **用例1: 创建项目**
  - **描述**: 项目经理可以创建新项目，并设置项目名称、描述、开始和结束日期。
  - **正常流程**:
    1. 项目经理访问项目管理界面。
    2. 项目经理点击创建项目并填写项目信息。
    3. 系统验证信息并创建项目。
    4. 项目出现在项目列表中，可供进一步操作。
  - **异常流程**:
    - 项目经理填写的信息不完整或不符合要求，系统提示错误并要求重新填写。

- **用例2: 分配任务**
  - **描述**: 项目经理可以为项目创建任务，并将其分配给团队成员。
  - **正常流程**:
    1. 项目经理选择项目并点击创建任务。
    2. 项目经理输入任务详情并选择执行者。
    3. 系统记录任务并通知分配的成员。
    4. 任务状态更新为“已分配”。
  - **异常流程**:
    - 项目经理尝试分配任务给不存在或未激活的账户，系统提示错误。

- **用例3: 跟踪进度**
  - **描述**: 团队成员可以更新任务进度，项目经理可以查看整体项目进度。
  - **正常流程**:
    1. 团队成员访问自己的任务列表。
    2. 成员选择任务并更新进度信息。
    3. 系统更新任务状态并计算项目整体进度。
    4. 项目经理查看更新后的项目进度。
  - **异常流程**:
    - 团队成员尝试更新非分配给自己的任务，系统阻止并提示错误。

### 2.4 服务管理模块
- **用例1: 配置服务**
  - **描述**: 管理员可以添加或修改IT服务的配置信息。
  - **正常流程**:
    1. 管理员访问服务管理界面。
    2. 管理员选择服务并进行配置更新。
    3. 系统更新服务配置并应用更改。
  - **异常流程**:
    - 管理员配置错误信息，系统提示错误并要求重新配置。

- **用例2: 监控服务状态**
  - **描述**: 系统实时监控IT服务状态，并在服务出现问题时通知相关人员。
  - **正常流程**:
    1. 系统实时监控服务状态。
    2. 服务出现问题时，系统自动记录并触发通知。
    3. 相关人员收到通知并响应。
  - **异常流程**:
    - 监控系统本身出现故障，备用监控系统未能及时接管，导致服务状态未被及时监控。

### 2.5 任务和工作流模块
- **用例1: 定义工作流程**
  - **描述**: 管理员可以定义项目任务的工作流程，包括任务类型、流转路径和完成条件。
  - **正常流程**:
    1. 管理员访问工作流定义界面。
    2. 管理员设计工作流程并保存。
    3. 系统应用新的工作流程定义。
  - **异常流程**:
    - 管理员定义的流程存在逻辑错误，系统提示错误并要求修正。

- **用例2: 自动化任务分配**
  - **描述**: 系统根据预定义的规则自动将任务分配给合适的团队成员。
  - **正常流程**:
    1. 系统检测到新任务或任务状态变更。
    2. 系统根据规则自动分配任务给团队成员。
    3. 分配的成员收到任务通知并开始执行。
  - **异常流程**:
    - 规则定义不明确或有冲突，导致系统无法决定任务分配，系统通知管理员进行手动干预。

### 2.6 报告和分析模块
- **用例1: 生成项目报告**
  - **描述**: 项目经理可以生成项目的状态报告，包括任务完成情况、成员贡献和时间线。
  - **正常流程**:
    1. 项目经理访问报告生成界面。
    2. 项目经理选择报告类型和时间范围。
    3. 系统生成并展示报告。
  - **异常流程**:
    - 缺少必要的数据，导致报告不完整，系统提示错误并要求补充数据。

- **用例2: 数据分析**
  - **描述**: 管理员可以利用系统提供的工具进行数据分析，识别项目风险和效率改进点。
  - **正常流程**:
    1. 管理员访问数据分析界面。
    2. 管理员选择分析工具和数据集。
    3. 系统进行数据分析并展示结果。
  - **异常流程**:
    - 分析工具失败或数据集错误，系统提示错误并要求重新选择。

### 2.7 通知和消息模块
- **用例1: 发送通知**
  - **描述**: 系统可以自动发送项目更新、任务分配和截止日期提醒等通知。
  - **正常流程**:
    1. 系统检测到通知触发条件。
    2. 系统发送通知到指定的用户。
  - **异常流程**:
    - 通知发送失败，可能是因为接收方邮箱或联系方式错误，系统记录失败事件并尝试重发。

- **用例2: 管理消息偏好**
  - **描述**: 用户可以设置通知偏好，选择接收通知的方式和时间。
  - **正常流程**:
    1. 用户访问账户设置界面。
    2. 用户选择通知偏好并保存。
    3. 系统根据用户的偏好发送通知。
  - **异常流程**:
    - 用户设置的偏好不正确或不明确，系统提示错误并要求重新设置。
### 2.8 财务模块
- **用例1: 定义WBS**
  - **描述**: 项目经理可以为每个项目创建一个或多个WBS，用于详细记录项目的人员工时和成本。
  - **正常流程**:
    1. 项目经理登录系统并选择项目。
    2. 项目经理创建WBS并定义其结构和任务。
    3. 系统保存WBS信息并与项目关联。
  - **异常流程**:
    - 项目经理尝试创建无项目关联的WBS，系统提示错误并要求选择项目。

- **用例2: 记录工时到WBS**
  - **描述**: 团队成员可以将自己的工时记录到指定项目的WBS上。
  - **正常流程**:
    1. 团队成员登录系统并选择关联的WBS。
    2. 成员输入工时信息并提交。
    3. 系统记录工时并更新WBS的总工时和成本。
  - **异常流程**:
    - 成员尝试记录到非关联的WBS，系统提示错误并要求选择正确的WBS。

- **用例3: 审核WBS工时**
  - **描述**: 项目经理或财务人员可以审核WBS上的工时记录，并进行批准或驳回。
  - **正常流程**:
    1. 审核人员登录系统并访问WBS工时审核界面。
    2. 审核人员选择待审核的工时记录并进行审查。
    3. 审核人员批准或驳回工时记录，系统更新记录状态。
  - **异常流程**:
    - 审核人员未在规定时间内完成审核，系统自动发送提醒通知。

- **用例4: 计算项目成本**
  - **描述**: 系统根据WBS上的工时记录和人员的工时单价自动计算项目的总成本。
  - **正常流程**:
    1. 系统自动汇总WBS的总工时。
    2. 系统根据工时单价计算成本并更新项目财务状况。
  - **异常流程**:
    - 工时单价数据缺失或错误，系统提示错误并阻止成本计算。

- **用例5: 生成财务报告**
  - **描述**: 财务人员可以生成包含成本分析、预算执行情况和利润预测的财务报告。
  - **正常流程**:
    1. 财务人员访问报告生成界面。
    2. 选择报告类型、项目和时间范围。
    3. 系统生成财务报告并展示。
  - **异常流程**:
    - 缺少必要的数据，导致报告不完整，系统提示错误并要求补充数据。

## 3. 应用架构

MetaBoard网站的应用架构由以下主要功能模块组成，每个模块都有其特定的职责和依赖关系：

- **用户认证模块**：处理用户的注册、登录、密码重置和会话管理。此模块独立于其他模块，但需要与用户管理模块进行交互以验证用户身份和权限。
- **用户管理模块**：管理用户的个人信息、账户设置、角色分配等。此模块依赖于用户认证模块来验证用户状态和权限。
- **项目管理模块**：提供项目创建、编辑、删除、任务分配、进度跟踪等功能。此模块依赖于用户管理模块以分配项目成员和设置权限。
- **服务管理模块**：管理IT服务的配置、监控、维护和支持。此模块可能依赖于第三方服务监控工具或API。
- **任务和工作流模块**：定义和管理项目任务、工作流程、自动化规则等。此模块依赖于项目管理模块以关联任务到项目。
- **报告和分析模块**：生成项目和服务的报告、分析数据、可视化仪表板。此模块依赖于项目管理模块和服务管理模块以获取数据。
- **通知和消息模块**：发送系统通知、电子邮件、短信和应用内消息。此模块依赖于其他模块以获取事件触发器和内容。
- **文件和文档管理模块**：存储、管理和共享项目相关的文件和文档。此模块依赖于项目管理模块以组织文件结构。
- **权限和访问控制模块**：定义角色、权限和访问控制规则。此模块与用户管理模块和项目管理模块紧密交互。
- **配置和设置模块**：管理应用设置、自定义选项和维护系统配置。此模块无直接依赖，但影响整个应用的行为。
- **第三方服务模块**：集成和管理外部API和服务。此模块与生成式AI Copilot模块交互，提供数据支持和增强功能。

## 4. 数据库设计

### 4.1 用户账户表 (users)
存储用户的个人账户信息和认证数据。

| 字段名          | 数据类型    | 描述             | 约束         |
| --------------- | ----------- | ---------------- | ------------ |
| id              | INT         | 用户唯一标识     | 主键, 自增   |
| name            | VARCHAR     | 用户名           |              |
| email           | VARCHAR     | 用户邮箱         | 唯一         |
| password_hash   | VARCHAR     | 加密后的密码     |              |
| remember_token  | VARCHAR     | 记住我 token     |              |
| created_at      | DATETIME    | 账户创建时间     |              |
| updated_at      | DATETIME    | 账户更新时间     |              |

### 4.2 用户角色表 (roles)
定义不同的用户角色及其名称。

| 字段名          | 数据类型    | 描述             | 约束         |
| --------------- | ----------- | ---------------- | ------------ |
| id              | INT         | 角色唯一标识     | 主键, 自增   |
| name            | VARCHAR     | 角色名称         | 唯一         |
| description     | TEXT        | 角色描述         |              |

### 4.3 用户角色关联表 (role_user)
存储用户和角色之间的关系。

| 字段名          | 数据类型    | 描述             | 约束         |
| --------------- | ----------- | ---------------- | ------------ |
| id              | INT         | 关联唯一标识     | 主键, 自增   |
| user_id         | INT         | 用户标识         |              |
| role_id         | INT         | 角色标识         |              |

### 4.4 项目表 (projects)
存储项目的基本信息。

| 字段名          | 数据类型    | 描述             | 约束         |
| --------------- | ----------- | ---------------- | ------------ |
| id              | INT         | 项目唯一标识     | 主键, 自增   |
| name            | VARCHAR     | 项目名称         |              |
| description     | TEXT        | 项目描述         |              |
| start_date      | DATE        | 项目开始日期     |              |
| end_date        | DATE        | 项目结束日期     |              |
| created_by      | INT         | 创建者用户ID     |              |
| updated_by      | INT         | 更新者用户ID     |              |
| created_at      | DATETIME    | 创建时间         |              |
| updated_at      | DATETIME    | 更新时间         |              |

### 4.5 任务表 (tasks)
存储项目中的任务信息。

| 字段名          | 数据类型    | 描述             | 约束         |
| --------------- | ----------- | ---------------- | ------------ |
| id              | INT         | 任务唯一标识     | 主键, 自增   |
| project_id      | INT         | 所属项目ID       |              |
| name            | VARCHAR     | 任务名称         |              |
| description     | TEXT        | 任务描述         |              |
| due_date       | DATE        | 任务截止日期     |              |
| status          | ENUM        | 任务状态         |              |
| created_by      | INT         | 创建者用户ID     |              |
| updated_by      | INT         | 更新者用户ID     |              |
| created_at      | DATETIME    | 创建时间         |              |
| updated_at      | DATETIME    | 更新时间         |              |

### 4.6 任务成员关联表 (task_members)
存储任务和成员之间的关系。

| 字段名          | 数据类型    | 描述             | 约束         |
| --------------- | ----------- | ---------------- | ------------ |
| id              | INT         | 关联唯一标识     | 主键, 自增   |
| task_id         | INT         | 任务标识         |              |
| user_id         | INT         | 用户标识         |              |

### 4.7 服务表 (services)
存储提供的IT服务信息。

| 字段名          | 数据类型    | 描述             | 约束         |
| --------------- | ----------- | ---------------- | ------------ |
| id              | INT         | 服务唯一标识     | 主键, 自增   |
| name            | VARCHAR     | 服务名称         |              |
| description     | TEXT        | 服务描述         |              |
| status          | ENUM        | 服务状态         |              |
| created_by      | INT         | 创建者用户ID     |              |
| updated_by      | INT         | 更新者用户ID     |              |
| created_at      | DATETIME    | 创建时间         |              |
| updated_at      | DATETIME    | 更新时间         |              |

### 4.8 服务监控表 (service_monitoring)
存储服务监控的数据和事件。

| 字段名          | 数据类型    | 描述             | 约束         |
| --------------- | ----------- | ---------------- | ------------ |
| id              | INT         | 监控记录唯一标识 | 主键, 自增   |
| service_id      | INT         | 服务标识         |              |
| status          | ENUM        | 监控状态         |              |
| message         | TEXT        | 监控信息         |              |
| occurred_at     | DATETIME    | 事件发生时间   |              |
- **WBS表 (wbss)**
  - 用于存储每个项目的WBS信息。

| 字段名          | 数据类型    | 描述             | 约束         |
| --------------- | ----------- | ---------------- | ------------ |
| id              | INT         | WBS唯一标识     | 主键, 自增   |
| project_id      | INT         | 项目标识         |              |
| name            | VARCHAR     | WBS名称           |              |
| description     | TEXT        | WBS描述         |              |

- **WBS任务关联表 (wbs_tasks)**
  - 存储WBS与项目任务的关联信息。

| 字段名          | 数据类型    | 描述             | 约束         |
| --------------- | ----------- | ---------------- | ------------ |
| id              | INT         | 关联唯一标识     | 主键, 自增   |
| wbs_id          | INT         | WBS标识         |              |
| task_id         | INT         | 任务标识         |              |

- **工时记录表 (time_records)**
  - 用于存储团队成员在WBS上的工时记录。

| 字段名          | 数据类型    | 描述             | 约束         |
| --------------- | ----------- | ---------------- | ------------ |
| id              | INT         | 记录唯一标识     | 主键, 自增   |
| wbs_task_id     | INT         | WBS任务关联标识 |              |
| user_id         | INT         | 用户标识         |              |
| hours           | DECIMAL      | 工时数量         |              |
| rate            | DECIMAL      | 工时单价         |              |
| created_at      | DATETIME    | 记录创建时间     |              |
| approved_at     | DATETIME    | 记录批准时间     |              |
| approver_id     | INT         | 审核者用户标识   |              |

## 5. API规范
- API应遵循RESTful架构原则，提供统一的接口。
- API应支持版本控制，以兼容未来的变更。
- API调用应通过OAuth或类似机制进行身份验证和授权


## Propsed slogans of Meta-Board from AI
 1. "Meta-Board: Crafting Your IT Success Story - Manage Portfolios, Projects & Services with Ease"
 2. "Master the Art of IT Management: Meta-Board - Streamline Your Portfolio, Projects & Services"
 3. "Meta-Board: Driving IT Forward - Unleash the Power of Collaborative Portfolio, Project & Service Management"
 4. "Achieve More Together: Meta-Board - Intuitive IT Project & Service Management for Maximum Impact"
 5. "Meta-Board: Simplifying IT Complexity - Manage Your Portfolios, Projects & Services with Confidence"
 6. "Innovate, Collaborate & Succeed: Meta-Board - The Complete Solution for Modern IT Management"
 7. "Meta-Board: Your IT Control Center - Seamlessly Manage Portfolios, Projects & Services for Success"
 8. "Empowering Progress: Meta-Board - IT Portfolio, Project & Service Management at Its Best"
 9. "Maximize Potential: Meta-Board - Streamline Your IT Operations with Our Portfolio, Project & Service Management Tool"
 10. "Meta-Board: Elevating IT Management - Achieve Excellence in Portfolio, Project & Service Delivery"
 11. "Meta-Board: Bridging the Gap Between Vision and Reality - Manage Your IT Portfolios, Projects & Services with Ease"
 12. "Revolutionize IT Management: Meta-Board - Empowering Teamwork, Boosting Efficiency & Enhancing Collaboration"
 13. "Meta-Board: Your Ultimate IT Management Companion - Simplify, Streamline & Succeed with Our Portfolio, Project & Service Management Solution"
 14. "Manage, Monitor & Master: Meta-Board - A Comprehensive IT Portfolio, Project & Service Management Platform"
 15. "Meta-Board: Connecting Ideas to Results - Manage Your IT Portfolios, Projects & Services with Confidence and Agility"
 16. "Accelerate Your IT Journey: Meta-Board - A Seamless Solution for IT Portfolio, Project & Service Management"
 17. "Meta-Board: Fostering Success - Empowering Effective IT Portfolio, Project & Service Management"
 18. "Unleash the Power of Teamwork: Meta-Board - Collaborate, Innovate & Succeed with Our IT Portfolio, Project & Service Management Platform"
 19. "Meta-Board: The Heart of Your IT Enterprise - Manage Your Portfolios,Projects & Services with Intuitive & Effective Tools".

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Meta-Board, please send an e-mail to Yuanhui Sun via [yuanhui.sun@hotmail.com](mailto:yuanhui.sun@hotmail.com). All security vulnerabilities will be promptly addressed.

## License

The Meta serise (include Meta-board) is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
